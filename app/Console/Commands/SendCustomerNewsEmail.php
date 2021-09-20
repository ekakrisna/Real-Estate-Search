<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;

use App\Models\Customer;
use App\Models\CustomerNew;

use SendGrid\Mail\Mail;
use SendGrid\Mail\From;
use SendGrid\Mail\To;
use SendGrid\Mail\SendAt;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SendCustomerNewsEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sendCustomerNewsEmail {options=null} {--isDisableSendingEmail}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function logInfo($output)
    {
        \Log::info($output);
        $this->info($output);
    }

    /**
     * Execute the console command.
     *
     * Send emails not sent yet.
     *
     * @return mixed
     */
    public function handle()
    {
        //------------------------------------------------------
        //Use try Catch for sending Email
        //------------------------------------------------------
        try {
            $this->logInfo('Execute email:sendCustomerNewsEmail');
            $options = $this->argument('options');
            $isDisableSendingEmail = $this->option('isDisableSendingEmail');

            $isSendEmail = true;
            $testTargetEmail = null;

            if ($options) {
                $contains = Str::contains($options, 'testTargetEmail');
                // -----------------------------------------------------------
                // Condition 2 If the command will be called as "email:sendCustomerNewsEmail testTargetEmail=****@***.*** ",
                // -----------------------------------------------------------
                if ($contains) {
                    $optionEmail = explode('=', $options);
                    $testTargetEmail = $optionEmail[1];
                    $isSendEmail = false;
                }
            }

            // -----------------------------------------------------------
            // Condition 1 If the command will be called as "email:sendCustomerNewsEmail --isDisableSendingEmail",
            // -----------------------------------------------------------
            if ($isDisableSendingEmail) {
                $isSendEmail = false;
            }

            $customerNews = CustomerNew::with(['customer', 'customer_news_property.property'])
                ->where(function ($query) {
                    // check customer_news.type
                    $query->where('type', CustomerNew::ADD_PROPERTY)
                        ->orWhere('type', CustomerNew::PROPERTY_UPDATE)
                        ->orWhere('type', CustomerNew::PROPERTY_END);
                })
                ->where('is_send_email', 0) // only email not sent yet.
                ->get();

            // get array of unique customer from customer news
            $customersId = [];
            foreach ($customerNews as $customerNew) {
                $customersId[] = $customerNew->customers_id;
            }
            $uniqureCustomersId = array_values(array_unique($customersId));

            $customersQuery = Customer::with('customer_news.customer_news_property.property')
                ->whereIn('id', $uniqureCustomersId);

            // [option] If set testTargetEmail, restrict it.
            if ($testTargetEmail) {
                $customersQuery = $customersQuery->where('email', $testTargetEmail);
            }

            $customers = $customersQuery->get();

            // Log report ------------------------------------------------------------
            $this->logInfo(sprintf("%d customerNews , %d customers", count($customerNews), count($customers)));

            // Execute to send email for each customer.
            foreach ($customers as $customer) {
                $this->createAndSendEmailToCustomer(
                    $customer,
                    $isSendEmail
                );
            }

        } catch (\Exception $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport(
                "Failed Send Emails Record",
                $e->getMessage()
            );
            //------------------------------------------------------
        }
    }

    // -----------------------------------------------------------
    //  Sending email process of one company
    // -----------------------------------------------------------
    private function createAndSendEmailToCustomer($customer, $isSendEmail)
    {
        $isSucceedInformingProcess = false;
        $to_email       = $customer->email;

        $htmlContent = self::createEmailContents($customer);

        // prepare email
        $from = new From('info@tochi-s.net', 'トチサーチ');
        $subject = "【トチサーチ】物件情報のお知らせ";
        $recipient = new To($to_email, $customer->name);
        $time = new SendAt(Carbon::now()->addMinutes(5)->timestamp);

        $email = new Mail();
        $email->setFrom($from);
        $email->setSubject($subject);
        $email->addTo($recipient);
        $email->addContent("text/html", $htmlContent);
        //$email->setSendAt($time);

        //------------------------------------------------------
        //ADD BCC EMAILS
        //------------------------------------------------------
        $bccEmails = [
            "tochi-s-report@grune.co.jp" => "Grune Staff" //user email and name
        ];
        $email->addBccs($bccEmails);
        //------------------------------------------------------

        if ($isSendEmail) {
            $this->logInfo(Carbon::now() . " - SendEmail");
            //------------------------------------------------------
            //Use try Catch for sending Email
            //------------------------------------------------------
            // send email
            //------------------------------------------------------
            $sendgrid = new \SendGrid(config('mail.SENDGRID_API_KEY'));
            $top30content = Str::limit(strip_tags(trim($htmlContent)), 30, $end = '...');
            try {
                $isSucceedInformingProcess = true;
                $sendgrid->send($email);
                //------------------------------------------------------
                // SAVE LOG INFO
                //------------------------------------------------------
                $this->logInfo(Carbon::now() . " - Send Emails Record: ", ['to' => $to_email, 'cc' => '', 'from' => $from, 'content' => $top30content]);
                //------------------------------------------------------
            } catch (\Exception $e) {
                $this->logInfo(Carbon::now() . " - Failed Send Emails Record: ", ['to' => $to_email, 'cc' => '', 'from' => $from, 'content' => $top30content, 'error' => $e->getMessage()]);
                sendMessageOfErrorReport(
                    "email content: " . $top30content
                );
            }
            //------------------------------------------------------
        } else {
            //------------------------------------------------------
            //Condition 1 send email Content to chatwork and dont send email
            //------------------------------------------------------
            $isSucceedInformingProcess = true;
            sendMessageOfErrorReport(
                "email content: - isDisableSendingEmail Command, not sending email but send email content to chatwork"
            );
        }


        if ($isSucceedInformingProcess) {
            // update customer news is send email to true after complete send email
            foreach ($customer->customer_news as $customerNew) {
                if (!$customerNew->is_send_email) {
                    $this->logInfo('Update customer_news.is_send_email=1 id:' . ($customerNew->id));
                    $customerNew->is_send_email = 1;
                    $customerNew->save();
                }
            }
        }
    }

    // -----------------------------------------------------------
    //  Prepare Email Content
    //  Input: customer model
    //  Output: one string(html contents)
    // -----------------------------------------------------------
    private static function createEmailContents($customer)
    {
        // generate list of new registered property (CustomerNew::ADD_PROPERTY)
        $newRegisteredProperty = '';
        foreach ($customer->customer_news as $customer_new) {
            if (
                !$customer_new->is_send_email /* This flag will be changed after sent */
                && $customer_new->type == CustomerNew::ADD_PROPERTY
                && count($customer_new->customer_news_property) > 0
            ) {
                foreach ($customer_new->customer_news_property as $customer_new_property) {
                    $min_price = toManDisplay($customer_new_property->property->minimum_price);
                    $max_price =  $customer_new_property->property->maximum_price == null ? '' : ' ~ ' . toManDisplay($customer_new_property->property->maximum_price);

                    $min_land_area = toTsubo($customer_new_property->property->minimum_land_area, '0,0') . '坪(' . $customer_new_property->property->minimum_land_area . '㎡)';
                    $max_land_area = $customer_new_property->property->maximum_land_area == null ? '' : ' ~ ' . toTsubo($customer_new_property->property->maximum_land_area, '0,0') . '坪(' . $customer_new_property->property->maximum_land_area . '㎡)';

                    $newRegisteredProperty .= '
                        <p>
                            住所 : ' . $customer_new_property->property->location . '<br>
                            価格 : ' . $min_price . $max_price . '<br>
                            土地面積 : ' . $min_land_area . $max_land_area . ' <br>
                            URL : <a href="' . $customer_new_property->property->url->frontend_view . '">' . $customer_new_property->property->url->frontend_view . '</a>
                        </p>
                    ';
                }
            }
        }

        // generate list of updated property (CustomerNew::PROPERTY_UPDATE)
        $updatedProperty = '';
        foreach ($customer->customer_news as $customer_new) {
            if (
                !$customer_new->is_send_email /* This flag will be changed after sent */
                && $customer_new->type == CustomerNew::PROPERTY_UPDATE
                && count($customer_new->customer_news_property) > 0
            ) {
                foreach ($customer_new->customer_news_property as $customer_new_property) {
                    $min_price = toManDisplay($customer_new_property->property->minimum_price);
                    $max_price =  $customer_new_property->property->maximum_price == null ? '' : ' ~ ' . toManDisplay($customer_new_property->property->maximum_price);

                    $min_land_area = toTsubo($customer_new_property->property->minimum_land_area, '0,0') . '坪(' . $customer_new_property->property->minimum_land_area . '㎡)';
                    $max_land_area = $customer_new_property->property->maximum_land_area == null ? '' : ' ~ ' . toTsubo($customer_new_property->property->maximum_land_area, '0,0') . '坪(' . $customer_new_property->property->maximum_land_area . '㎡)';

                    $updatedProperty .= '
                        <p>
                            住所 : ' . $customer_new_property->property->location . '<br>
                            価格 : ' . $min_price . $max_price . '<br>
                            土地面積 : ' . $min_land_area . $max_land_area . ' <br>
                            URL : <a href="' . $customer_new_property->property->url->frontend_view . '">' . $customer_new_property->property->url->frontend_view . '</a>
                        </p>
                    ';
                }
            }
        }

        // generate list of deleted property(CustomerNew::PROPERTY_END)
        $deletedProperty = '';
        foreach ($customer->customer_news as $customer_new) {
            if (
                !$customer_new->is_send_email /* This flag will be changed after sent */
                && $customer_new->type == CustomerNew::PROPERTY_END
                && count($customer_new->customer_news_property) > 0
            ) {
                foreach ($customer_new->customer_news_property as $customer_new_property) {
                    $min_price = toManDisplay($customer_new_property->property->minimum_price);
                    $max_price =  $customer_new_property->property->maximum_price == null ? '' : ' ~ ' . toManDisplay($customer_new_property->property->maximum_price);

                    $min_land_area = toTsubo($customer_new_property->property->minimum_land_area, '0,0') . '坪(' . $customer_new_property->property->minimum_land_area . '㎡)';
                    $max_land_area = $customer_new_property->property->maximum_land_area == null ? '' : ' ~ ' . toTsubo($customer_new_property->property->maximum_land_area, '0,0') . '坪(' . $customer_new_property->property->maximum_land_area . '㎡)';

                    $deletedProperty .= '
                        <p>
                            住所 : ' . $customer_new_property->property->location . '<br>
                            価格 : ' . $min_price . $max_price . '<br>
                            土地面積 : ' . $min_land_area . $max_land_area . ' <br>
                        </p>
                    ';
                }
            }
        }

        if ($newRegisteredProperty == '') {
            $newRegisteredProperty = "なし";
        }

        if ($updatedProperty == '') {
            $updatedProperty = "なし";
        }

        if ($deletedProperty == '') {
            $deletedProperty = "なし";
        }

        $htmlContent = "
            <h2>■■更新があった物件情報をお知らせいたします■■</h2>
            <p>（本メールは物件情報をお客様にお送りしている自動返信メールです。返信はできません。）</p>
            <p>$customer->name 様</p>
            <p>
                お世話になっております。トチサーチ運営事務局でございます。<br>
                お客様のお気に入りエリア内で情報の更新があった物件情報を送信させていただいております。
            </p>
            <br>
            <p>■新規登録物件</p>
            $newRegisteredProperty
            <p>■更新があった物件</p>
            $updatedProperty
            <p>■削除された物件</p>
            $deletedProperty
            <br>
            <p>
            ※なお万が一身に覚えのない場合は、お手数ですが運営事務局までご連絡をお願いたします。<br>
            →お問い合わせ <br>
            info@tochi-s.net
        </p>
        <p>
            ——————————————————————— <br>
            サイトURL：<a href='https://tochi-s.net'>https://tochi-s.net</a>
        </p>
        <p>
            【会社情報】<br>
            株式会社Beyond-ets <br>
            〒990-0042 山形市七日町二丁目7番46号 <br>
            TEL:050-3733-5390 <br>
            営業時間：平日 9時～18時 <br>
            ———————————————————————
        </p>
        ";
        return $htmlContent;
    }
}
