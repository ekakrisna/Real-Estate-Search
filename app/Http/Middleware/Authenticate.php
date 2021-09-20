<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            //if(Auth::guard('user')->check()){
                //return abort(401);
            //}

            if ($request->is('admin') || $request->is('admin/*')) {
                return route('login');
            }
            if ($request->is('manage') || $request->is('manage/*')) {
                return route('login');
            }
            return route('customer-login');
        }

    }
}
