<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class UserRolePermission
{
    /**
     * Handle an incoming request.
     * db admin_roles->name == name
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param array                    $role
     * @return mixed
     */
    public function handle($request, Closure $next, ...$role){
        //dd($role);
        //if(in_array(Auth::guard('user')->user()->userRole->name, $role))
        //{
            if(!empty( $request->hasSession('language') )){
                App::setLocale( $request->session()->get('language') );
            }

            return $next($request);
        //}
        //else{
            //return redirect('/login');
        //}
    }
}
