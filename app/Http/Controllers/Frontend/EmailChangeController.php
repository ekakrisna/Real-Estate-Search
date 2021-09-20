<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerResetEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Throwable;

class EmailChangeController extends Controller
{

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email'                   => 'required|email|max:255|unique:customers,email,' . Auth::guard('user')->user()->id,
        ]);
    }

    public function index()
    {
        if (Auth::guard('user')->user() === null) {
            return abort(404);
        }
        $id = Auth::guard('user')->user()->id;
        $data['customer_detail'] = Customer::where('id', $id)->first();
        $data['title'] =  __('label.enterEmailAddress') . __('label.change');
        return view('frontend.account_setting.change_mail_address', $data);
    }

    public function edit(Request $request)
    {
        if (Auth::guard('user')->user() === null) {
            return abort(404);
        }
        $id = Auth::guard('user')->user()->id;
        // ------------------------------------------------------------------
        $response = new \stdClass;
        // ------------------------------------------------------------------
        try {
            $response->status = 'success';
            $dataset = json_decode($request->dataset, true);

            // Set Default Value
            $customer_data = new CustomerResetEmail;
            $customer_data['customer_id']   = $id;
            $customer_data['created_at']    = Carbon::now();
            $customer_data['new_email']     = $dataset['email']['new_email'];
            $customer_data['is_adaptation'] = false;
            $customer_data['text']          = Str::random(60);
            $customer_data->save();


            $customer = CustomerResetEmail::with('customer')->where('customer_id', $id)->where('is_adaptation', 0)->latest()->first();

            //SEND MESSAGE EMAIL
            // foreach ($customer as $key => $value) {
            $to_name    = $customer->customer->name;
            $to_email   = $customer->new_email;
            $text       = $customer->token;
            // }

            $token = route('frontend.change_email.token', $text);
            //$token = route('frontend.change_email.token').'?token='.$text;

            $data = array(
                'title' => "Real Estate Change Email", 'body_first' => "$to_name 様 日頃より、トチサーチを利用いただきありがとうございます。",
                'body_third' => "以下のURLより、クリックし変更手続きを進めてください。",
                'reset_link' => $token, 'body_second' => "※本メールに心当たりが無い場合、削除をお願いします。"
            );

            Mail::send('frontend.email.change_mail', $data, function ($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('Change Email');
                $message->from('info@grune.co.jp', 'Grune');
            });

            Auth::guard('user')->logout();
            // ------------------------------------------------------------------
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            // ------------------------------------------------------------------

        } catch (Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Frontend/EmailChangeController (edit Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = 'failed';
            $response->message = $e;

            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }

    public function aftersend(Request $request)
    {
        return view('frontend.account_setting.change_mail_address_send');
    }

    public function complete(Request $request)
    {
        return view('frontend.account_setting.change_mail_address_complete');
    }

    public function update($token, Request $request)
    {
        $customer = CustomerResetEmail::where('token', $token)->where('is_adaptation', false)->first();
        // if reset email record is already used trance to login page
        if ($customer == null) {
            return redirect()->route('customer-login');
        }

        $customer->is_adaptation = 1;
        $customer->save();

        $customer     = Customer::find($customer->customer_id);
        $customer->email = $customer->new_email;
        $customer->save();

        return redirect()->route('frontend.change_email.complete');
    }
}
