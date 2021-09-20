<?php

namespace App\Http\Middleware;

use App\Models\CompanyRole;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        
        if(Auth::guard("web")->check()){
            //if(Auth::user()->company_user_roles_id == CompanyRole::ROLE_ADMIN){
                //\Log::debug('RedirectIfAuthenticated: Redirect to super admin edit');
                //return redirect()->route('admin.superadmin.edit', ['superadmin' => Auth::user()->id]);
            //}else{
                //\Log::debug('RedirectIfAuthenticated: Redirect to admins edit');
                //return redirect()->route('admin.admins.edit', ['admin' => Auth::user()->id]);
            //}
            if(Auth::user()->company->company_roles->id == CompanyRole::ROLE_ADMIN){
                return redirect()->route('admin.index');
            }

            if(Auth::user()->company->company_roles->id == CompanyRole::ROLE_HOME_MAKER){
                return redirect()->route('manage.index');
            }
        }
        
        /*
        if(Auth::guard("user")->check()){
            \Log::debug('RedirectIfAuthenticated: Redirect to userowner edit');
            return redirect()->route('frontend.start');
        }
        */
        return $next($request);
    }
}
