<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Auth;
// --------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
// --------------------------------------------------------------------------
use App\Models\CompanyRole;
use App\Models\CompanyUserLogActivity;
use Session;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class AdminLoginController extends Controller {
    // ----------------------------------------------------------------------
    use AuthenticatesUsers;
    // ----------------------------------------------------------------------
    protected $redirectTo = '/admin/admins'; // Where to redirect users after login.
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }
    // ----------------------------------------------------------------------

    public function logout()
    {
        Auth::logout();

        if(Session::has('errors')){
            return redirect('/admin/login')
            ->withErrors([Session::get('errors')->first()]);
        }

        return redirect('/admin/login');
        
    }

    // ----------------------------------------------------------------------
    protected function loggedOut( Request $request ){
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    protected function authenticated(Request $request, $user){
        Auth::guard('user')->logout();
        
        // ----------------------------------------------------------------------
        // @ Function to store process of companyUserLogActivity
        // ----------------------------------------------------------------------
        CompanyUserLogActivity::storeCompanyUserLog($user,CompanyUserLogActivity::LOGIN,null);
        // ----------------------------------------------------------------------

        switch ($user->company->company_roles->id){
            case CompanyRole::ROLE_ADMIN:
                return redirect()->intended('admin');
                //return redirect()->route('admin.index');
            case CompanyRole::ROLE_HOME_MAKER:
                //return redirect()->route('admin.company.user.index', $user->company->id);
                return redirect()->intended('manage');
                //return redirect()->route('manage.index');
            default:
                return redirect('/dashboard');
        }
    }
}
