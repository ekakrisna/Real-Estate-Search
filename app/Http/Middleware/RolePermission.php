<?php

namespace App\Http\Middleware;

use App\Models\CompanyRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class RolePermission
{
    /**
     * Handle an incoming request.
     * db admin_roles->name == name
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param array                    $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$role){
       
        //if(in_array(Auth::user()->company->company_roles->name,$role))
        //{
            /** [secutiry] Restrict to edit ANOTHER COMPANY data **/
            /**
             *  $request->route()->parameter('company') get parameter of URL.
             *  ex. company/{company}/user -> get id by parameter('company')
             */
            /*
            if( Auth::user()->company->company_roles->id == CompanyRole::ROLE_HOME_MAKER){
                //&& $request->route()->parameter('company') != Auth::user()->company->id ){
                //abort(404, "User cannot access to this company. ");
            }
            */

            if(!empty( $request->hasSession('language') )){
                App::setLocale( $request->session()->get('language') );
            }

            return $next($request);
        //}
        //else{
            //return $next($request);
            //return \App::abort(404);
        //}
    }
}
