<?php


namespace App\Http\Middleware;

use App\Models\CompanyRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompanyRolePermission
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
        if(in_array(Auth::user()->company->company_roles->name, $role))
        {
            return $next($request);
            
        }else{
            if(Str::contains($request->route()->getPrefix(), 'admin')){
                return redirect('admin/logout')->withErrors(['You must login as Admin Role to access Admin modules']);
            }

            if(Str::contains($request->route()->getPrefix(), 'manage')){
                return redirect('admin/logout')->withErrors(['You must login as House Maker Role to access Manage modules']);
            }

            return redirect('admin/logout');
        }
    }
}
