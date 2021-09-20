<?php

namespace App\Http\Controllers\Frontend;

use Redirect;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\CustomerLogActivity;
use App\Models\ActionType;
use App\Models\CustomerContactUs;
use App\Models\ContactUsType;
use App\Models\CustomerResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class PasswordResetController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'         => 'required|email|max:255',
        ]);
    }

    protected function validatorForgotEmail(array $data)
    {
        return Validator::make($data, [
            'name'          => 'required',
            'company_name'  => 'required|max:50',
            'company_email' => 'required|email|max:255'
        ]);
    }

    public function index()
    {
        $data = new \stdClass;
        $data->contact_us = factory(CustomerContactUs::class)->state('init')->make();
        return view('frontend.password_reissue.index', (array) $data);
    }

    public function sendemail(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'failed';
        $response->message = '該当のメールアドレスは存在しません。';
        $dataset = json_decode($request->dataset);
        // ------------------------------------------------------------------

        try {
            $email              = $dataset->sendEmail->email;
            $currentCustomer    = Customer::where('email', $email)->first();

            if ($currentCustomer) {
                //IF the value of customers.not_leave_record of customer is false, set action_types_id of customer_log_activities to 6 and register the history
                //if( $currentCustomer->not_leave_record == FALSE || $currentCustomer->not_leave_record == "0"){
                //$log                        = new CustomerLogActivity();
                //$log->customers_id          = $currentCustomer->id;
                //$log->action_types_id       = ActionType::RESET_PASSWORD;
                //$log->ip                    = $request->ip();
                //$log->access_time           = Carbon::now();
                //$log->save();
                //}

                //---------------------------------------------------
                //Save customer log activity when sendemail
                //---------------------------------------------------
                CustomerLogActivity::storeCustomerLog(ActionType::RESET_PASSWORD, $currentCustomer->id, $request->ip());
                //---------------------------------------------------

                //SEND MESSAGE EMAIL
                $to_name = $currentCustomer->name;
                $to_email = $currentCustomer->email;

                $customer_data = new CustomerResetPassword;
                $customer_data['customer_id']   = $currentCustomer->id;
                $customer_data['created_at']    = Carbon::now();
                $customer_data['is_adaptation'] = false;
                $customer_data['token']         = Str::random(60);
                $customer_data->save();


                $customer = CustomerResetPassword::with('customer')->where('customer_id', $currentCustomer->id)->where('is_adaptation', 0)->latest()->first();

                //SEND MESSAGE EMAIL
                $token       = $customer->token;

                $token = route('password_reissue.token') . '/' . '?token=' . $token;

                $data = array('title' => "Real Estate Reset Password", 'body' => "Hello $to_name please click this button to reset your password", 'reset_link' => $token);

                Mail::send('frontend.email.reset_password', $data, function ($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)->subject('Reset Password');
                    $message->from('info@tochi-s.net', 'トチサーチ');
                });


                $response->status = 'success';
                $response->message = 'パスワードのリセットのメールを送信しました。';
            }

            // --------------------------------------------------------------
            // Send Response on success
            // --------------------------------------------------------------
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            // ------------------------------------------------------------------

        } catch (Throwable $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Frontend/PasswordResetController (sendemail Function), Error: ", $e);
            //------------------------------------------------------
            $response->status = 'failed';
            $response->message = $e;

            // --------------------------------------------------------------
            // Send Response on failed
            // --------------------------------------------------------------
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            // --------------------------------------------------------------
        }
    }

    public function forgotemail(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $dataset = json_decode($request->dataset);
        // ------------------------------------------------------------------

        try {
            //dd($dataset->contactUs);
            // ------------------------------------------------------------------
            // Set Default Value
            // ------------------------------------------------------------------
            $dataset->contactUs->flag               = false;
            $dataset->contactUs->is_finish          = false;
            $dataset->contactUs->created_at         = Carbon::now();
            $dataset->contactUs->updated_at         = Carbon::now();
            $dataset->contactUs->contact_us_types_id = ContactUsType::FORGOT_EMAIL;
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Debugging & Validating
            // ------------------------------------------------------------------
            //dd($dataset);
            //$this->validatorForgotEmail($dataset->contactUs)->validate();
            // ------------------------------------------------------------------

            // --------------------------------------------------------------
            // Create the CustomerContactUs
            // --------------------------------------------------------------
            $CustomerContactUs = CustomerContactUs::create((array) $dataset->contactUs);
            $response->CustomerContactUs = $CustomerContactUs; // Add to the JSON response
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Send Response on success
            // --------------------------------------------------------------
            $response->status = 'success';
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            // ------------------------------------------------------------------

        } catch (Throwable $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Frontend/PasswordResetController (forgotemail Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = 'failed';
            $response->message = $e;

            // --------------------------------------------------------------
            // Send Response on failed
            // --------------------------------------------------------------
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            // ------------------------------------------------------------------
        }
    }

    public function token(Request $request)
    {
        try {
            if ($request->token != null) {
                $token['token'] = $request->token;
                $customer = CustomerResetPassword::with('customer')->where('token', $request->token)->where('is_adaptation', 0)->latest()->first();
                if ($customer) {
                    return view('frontend.password_reissue.reset', $token);
                } else {
                    abort(404);
                }
            } else {
                abort(404);
            }
        } catch (\Throwable $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Frontend/PasswordResetController (token Function), Error: ", $e);
            //------------------------------------------------------

            throw $e;
        }
    }

    public function adapt(Request $request)
    {
        $response = new \stdClass;
        try {
            $dataset    = json_decode($request->dataset);
            $token      = $dataset->password->token;
            $email      = $dataset->password->email;
            $password   = $dataset->password->new_password;

            $customerReset  = CustomerResetPassword::with('customer')->where('token', $token)->where('is_adaptation', 0)->latest()->first();
            if ($customerReset == null) {
                $response->status = 'failed';
                $response->message = "既に使用済みのメールアドレスです。再度メールを送信してください。";
            } elseif ($customerReset->customer->email != $email) {
                $response->status = 'failed';
                $response->message = "メールアドレスが異なります。";
            } else {
                Auth::guard('user')->logout();

                $customerReset->is_adaptation = true;
                $customerReset->save();

                $passwordHash   = Hash::make($password);
                $customer   = $customerReset->customer::where('email', $email)->first();
                $customer->password = $passwordHash;
                $customer->save();

                $response->status = 'success';
                $response->redirect = route('password_reissue.complete');
            }

            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Frontend/PasswordResetController (adapt Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
    }

    public function aftersend(Request $request)
    {
        return view('frontend.password_reissue.change_password_address_send');
    }

    public function complete(Request $request)
    {
        return view('frontend.password_reissue.change_password_address_complete');
    }
}
