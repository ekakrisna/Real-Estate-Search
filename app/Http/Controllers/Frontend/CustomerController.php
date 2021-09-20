<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\Customer;
use App\Models\CustomerDesiredArea;
use App\Models\CustomerLogActivity;
use App\Models\CustomerSignUp;
use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;
use App\Models\ActionType;

use Illuminate\Support\Facades\Mail;
use stdClass;
use Throwable;
use Illuminate\Support\Facades\Hash;
use App\Traits\LogActivityTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    public function __construct()
    {
    }

    public function start()
    {
        $id = Auth::guard('user')->user()->id;
        $data['title'] = __('label.start');
        $data['customer_detail']    = Customer::where('id', $id)->first();
        $data['consider_amount']    = ListConsiderAmount::get();
        $data['land_area']          = ListLandArea::get();
        $data['desired_area']       = CustomerDesiredArea::with('town', 'city.prefecture', 'city_areas')->where('customers_id', $id)->get();
        return view('frontend.start.index', $data);
    }

    public function signup(Request $request)
    {
        $data['title'] = __('label.signup');
        $urlRequest = $request->all();

        $data['signedUpCustomer']       = null;
        $data['consider_amount']        = ListConsiderAmount::get();
        $data['land_area']              = ListLandArea::get();
        $data['max_consider_amount']    = ListConsiderAmount::max('value');
        $data['max_land_area']          = ListLandArea::max('value');

        if (!isset($urlRequest['step'])) {
            return redirect()->route('signup.email', ['step' => 1]);
        }

        if (isset($urlRequest['step']) && $urlRequest['step'] == 3) {

            if (!isset($urlRequest['token'])) {
                return redirect()->route('signup.email', ['step' => 1]);
            }


            $signedUpCustomer   = CustomerSignUp::where('token', $request->token)->first();
            if ($signedUpCustomer) {

                // all condtion to redirect to C18-6
                $expiredDate        = Carbon::parse($signedUpCustomer->created_at)->addHour(24);
                $currentDate        = Carbon::now();
                $isTokenExpired     = $currentDate > $expiredDate;
                $isAdapt            = $signedUpCustomer->is_adapt;

                if ($isTokenExpired || $isAdapt) {
                    return redirect()->route('signup.disabled_token');
                } else {
                    $data['signedUpCustomer']       = $signedUpCustomer;
                }
            } else {
                return redirect()->route('signup.disabled_token');
            }
        }

        return view('frontend.signup.index', $data);
    }

    public function disabledToken()
    {
        $data['title'] = __('label.signup');
        return view('frontend.signup.disabled_token', $data);
    }

    //Sign up store email & create token
    public function signupWithEmail(Request $request)
    {
        try {
            $dataset    = json_decode($request->dataset);
            $email      = $dataset->email;
            $token      = bcrypt($dataset->uuid);
            //Check if customer email exists in customer_sign_up table and is_adapt is true
            $customerSignUp =  CustomerSignUp::where('email', $email)->where('is_adapt', 1)->first();
            //if customer's email not exists in customer_sign_up table
            if ($customerSignUp) {
                $token = $customerSignUp->token;
            } else {
                CustomerSignUp::updateOrCreate(
                    [
                        'email'     => $email,
                    ],
                    [
                        'token'     => $token
                    ]
                );
            }
            $response = new \stdClass;
            $response->token    = $token;
            $response->status   = 'success';
            return $this->sendEmailSignUp($email, $token);
        } catch (\Throwable $t) {
            throw $t;
        }
    }

    public function registerCustomer(Request $request)
    {
        $data = $request->all();
        $response = new \stdClass;

        if (isset($data['customer']['password'])) $data['customer']['password'] = bcrypt($data['customer']['password']);

        $data['customer']['flag']               = false;
        $data['customer']['is_cancellation']    = false;
        $data['customer']['not_leave_record']   = false;
        $data['customer']['license']            = true;

        try {
            $customer = Customer::create($data['customer']);

            foreach ($data['customer_desired_areas'] as $desired_area) {
                $desired_area['customers_id'] = $customer->id;
                $desired_area['cities_id'] = $desired_area['city']['id'];
                $desired_area['cities_area_id'] = null;
                if ($desired_area['cities_area']) {
                    $desired_area['cities_area_id'] = $desired_area['cities_area']['id'];
                }
                $desired_area['created_at'] =  Carbon::now();

                CustomerDesiredArea::create($desired_area);
            }

            //$cust_log = new CustomerLogActivity();
            //$cust_log->customers_id = $customer->id;
            //$cust_log->action_types_id = ActionType::SIGN_UP;
            //$cust_log->ip = $request->ip();
            //$cust_log->access_time = Carbon::now();
            //$cust_log->save();

            //---------------------------------------------------
            //Save customer log activity when registerCustomer
            //---------------------------------------------------
            CustomerLogActivity::storeCustomerLog(ActionType::SIGN_UP, $customer->id, $request->ip());
            //---------------------------------------------------

            $customerSignUp = CustomerSignUp::where('token', $request->token)->where('email', $data['customer']['email'])->first();
            $customerSignUp->is_adapt = 1;
            $customerSignUp->save();

            $response->status   = 'success';
            $response->customer = $customer;
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Frontend/CustomerController (registerCustomer Function), Error: ", $th);
            //------------------------------------------------------

            $response->status   = 'failed';
            $response->message  = $th->getMessage();
            return response()->json($response);
        }
    }

    public function sendEmailSignUp($email, $token)
    {
        $response = new \stdClass;
        try {
            // Prepare Email Content
            $to_email       = $email;
            $signup_url     = route('signup.email', ['step' => 3, 'token' => $token]);
            $contact_us_url = route('contact');

            $data = [
                'title'         => 'トチサーチに会員登録(無料)をしていただき、誠にありがとうございます。',
                'body_line_1'   => '▼以下のURLをクリックして、会員登録を行ってください。' . PHP_EOL,
                'signup_url'    => $signup_url,
                'body_line_2'   =>
                '====================================' . PHP_EOL .
                    '【ご注意ください!】' . PHP_EOL .
                    '◆このメールを受信されただけでは、登録は完了しません。' . PHP_EOL .
                    '◆本登録のURLはこのメール発効後、24時間有効です。' . PHP_EOL .
                    '24時間経過したい場合は、お手数ですが再度新規会員登録ページからご登録いただけるようにお願いいたします。' . PHP_EOL .
                    '▼お問い合わせ' . PHP_EOL,
                'contact_us_url'    =>  $contact_us_url,
                'body_line_3'   => '====================================',
            ];
            //SEND MESSAGE EMAIL
            Mail::send('frontend.email.signup', $data, function ($message) use ($to_email) {
                $message->to($to_email)->subject('【トチサーチ】仮登録の完了');
                $message->from('info@tochi-s.net', 'トチサーチ');
            });

            $response->status   = 'success';
            $response->messages = 'パスワードのリセットのメールを送信しました。';
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        } catch (Throwable $e) {
            $response->status   = 'failed';
            $response->message  = $e->getMessage();
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }

    public function customerInfo(Request $request)
    {
        $signedUpCustomer = CustomerSignUp::where('token', $request->token)->first();
        return response($signedUpCustomer);
    }
    public function sendEmail()
    {
        return response('OK');
    }
}
