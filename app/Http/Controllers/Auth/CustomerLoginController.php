<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SocialAccount;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\CustomerLogActivity;
use App\Models\ActionType;

class CustomerLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     * @var string
     */
    protected $redirectTo = '/user';

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }

    protected function loggedOut(Request $request) {
        return redirect()->route('customer-login');
    }

    protected function authenticated(Request $request, $user)
    {
        Auth::guard('web')->logout();
        return redirect()->route('user');
    }

    public function guard()
    {
        return auth()->guard('user');
    }

    protected function showLoginForm(){
        $data['title'] = __('label.login');
        return view('frontend.login.index',$data);
    }

    protected function login(Request $request)
    {
        Auth::guard('web')->logout();
        $remember = $request->stay_login ? true : false;

        // ----------------------------------------------------------------------
        //CHECK FOR CUSTOMER ID WAS A SOCIAL MEDIA ACCOUNT OR NOT
        // ----------------------------------------------------------------------
        $isSosial = 0;
        $isCancelled = 0;
        $checkCustomer = Customer::where('email', $request->email)->first();
        if($checkCustomer){
            $custId = $checkCustomer->id;

            //isSosial condition
            $checkSocial = SocialAccount::where('customers_id', $custId)->first();
            if($checkSocial){
                $isSosial = 1;
            }

            //isCancelled condition
            $checkCancelled = $checkCustomer->is_cancellation;
            if($checkCancelled){
                $isCancelled = 1;
            }
        }

        //Back with error if user already registered with social media account
        if($isSosial){
            return back()->withErrors(['errorlogin' => 'あなたのメールはソーシャルメディアアカウントとして登録されました。ソーシャルメディアアカウントでログインしてください']);
        }

        //Back with error if user already cancelled
        if($isCancelled){
            return back()->withErrors(['errorlogin' => 'アカウントは停止されています。詳細はお問い合わせください。']);
        }
        // ----------------------------------------------------------------------


        if (auth()->guard('user')->attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $customer_id = auth()->guard('user')->user()->id;
            //$cust_log = new CustomerLogActivity();
            //$cust_log->customers_id =  $customer_id;
            //$cust_log->action_types_id = ActionType::LOGIN;
            //$cust_log->ip = $request->ip();
            //$cust_log->access_time = Carbon::now();
            //$cust_log->save();

            //---------------------------------------------------
            //Save customer log activity when User Logged IN
            //---------------------------------------------------
            CustomerLogActivity::storeCustomerLog(ActionType::LOGIN, $customer_id, $request->ip());
            //---------------------------------------------------

            return redirect()->route('frontend.start');
        }
        return back()->withErrors(['errorlogin' => 'メールアドレスまたはパスワードが間違っています。 ']);
        
        
    }
}
