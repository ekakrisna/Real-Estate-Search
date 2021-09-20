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

class SendDailyReportToStaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:sendDailyReportToStaff';

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
            $this->logInfo("Execute email:sendDailyReportToStaff");
            // Execute totaling report
            $customerNewsQuery = CustomerNew::with(['customer', 'customer_news_property.property'])
            ->where(function ($query) {
                $query->where('type', CustomerNew::ADD_PROPERTY)
                    ->orWhere('type', CustomerNew::PROPERTY_UPDATE)
                    ->orWhere('type', CustomerNew::PROPERTY_END);
            });
            // 追加予定「通知対象の物件数・新たに承認待ちに入った物件数」
            $TodaysEmailQuery = $customerNewsQuery
                ->where('updated_at', '>' , Carbon::today() )
                ->where('updated_at', '<' , Carbon::today()->addDay(1) );
            $YestardayEmailQuery = $customerNewsQuery
                ->where('updated_at', '>' , Carbon::today()->addDay(-1) )
                ->where('updated_at', '<' , Carbon::today() );
            $TwoDaysAgoEmailQuery = $customerNewsQuery
                ->where('updated_at', '>' , Carbon::today()->addDay(-2) )
                ->where('updated_at', '<' , Carbon::today()->addDay(-1) );

            $TodaysEmailCount = $TodaysEmailQuery->count();
            $TodaysEmailNotSentCount = $TodaysEmailQuery->where('is_send_email', 0)->count();

            $YestardayEmailCount = $YestardayEmailQuery->count();
            $YestardayEmailNotSentCount = $YestardayEmailQuery->where('is_send_email', 0)->count();

            //Create Message
            $sendMessage = "[Automatically send message to Grune staff]  \n";
            $sendMessage .= "Today: ".Carbon::today()."\n";
            $sendMessage .= "TodaysEmailCount: $TodaysEmailCount \n";
            $sendMessage .= "TodaysEmailNotSentCount: $TodaysEmailNotSentCount \n";

            $sendMessage .= "Yesterday: ".( Carbon::today()->addDay(-1) )."\n";
            $sendMessage .= "YestardayEmailCount: $YestardayEmailCount \n";
            $sendMessage .= "YestardayEmailNotSentCount: $YestardayEmailNotSentCount \n";


            $sendMessage .= "TodaysEmailCount: $TodaysEmailCount \n";
            $sendMessage .= "TodaysEmailNotSentCount: $TodaysEmailNotSentCount \n";

            sendMessageViaChatworkCustomID('204191276', $sendMessage );

        } catch (\Exception $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport(
                "Failed DailyReportToStaff",
                $e->getMessage()
            );
            //------------------------------------------------------
        }
    }

}
