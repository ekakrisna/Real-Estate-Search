<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

use App\Models\CustomerLogActivity;
use App\Models\CustomerFavoriteProperty;
use App\Models\CustomerSearchHistory;
use App\Models\CustomerContactUs;
use App\Models\Customer;
use App\Models\CompanyUserRole;
use App\Models\CompanyUserTeam;

class DashboardController extends Controller
{
    public function index()
    {
        $data['page_title']     = __('label.dashboard');
        $company_user_role_id   = auth()->user()->company_user_roles_id;
      
        $data['customer_log_activites']     = $this->getCustomerLogActivity($company_user_role_id);
        $data['customer_search_histories']   = $this->getCustomerSearchHistory($company_user_role_id);

        return view('manage.dashboard.index', $data);
    }

    private function getCustomerLogActivity($company_user_role){

        // define for reration
        $customer_log_activity_realtions = [
            'customer', 'action_type', 'customer.favorite_properties', 
            'customer.properties_checked', 'customer.company_user.company'];

        // get common query
        $query = CustomerLogActivity::with($customer_log_activity_realtions);

        switch($company_user_role){

            // --------------------------------------------------------------
            // if company user role is 1 (CORPORATE_MANAGER) show all 
            // company user with same company id
            // --------------------------------------------------------------
            case CompanyUserRole::CORPORATE_MANAGER :
                $company_id = auth()->user()->companies_id;
                $query->whereHas( 'customer.company_user', function( Builder $sale ) use( $company_id ){
                    $sale->where( 'companies_id', $company_id  );
                });
            break;

            // --------------------------------------------------------------
            // if company user role is 2 (TEAM_MANAGER) show all 
            // customer with leader id  in company user team member table
            // customer with leader id  in company user team member table
            // --------------------------------------------------------------
            case CompanyUserRole::TEAM_MANAGER :
                // get all member id
                $memberID = CompanyUserTeam::where('leader_id', auth()->user()->id)->pluck('member_id')->toArray();
                // add leader id
                array_push($memberID, auth()->user()->id);
                $query->whereHas('customer.company_user', function( Builder $sale ) use( $memberID ) {
                    $sale->whereIn( 'id', $memberID  );
                });
            break;

            // --------------------------------------------------------------
            // if company user role is 3 (SALES_STAFF) show all 
            // customer with same company_users_id
            // --------------------------------------------------------------
            case CompanyUserRole::SALES_STAFF :
                $staffID = auth()->user()->id;
                $query->whereHas('customer.company_user', function( Builder $sale ) use( $staffID ) {
                    $sale->where( 'id', $staffID  );
                });
            break;
        }
        return $query->orderBy('id', 'desc')->take(5)->get();
    }

    private function getCustomerSearchHistory($company_user_role){
     
        // get common query
        $query = CustomerSearchHistory::with(['customer.company_user.company']);

        switch($company_user_role){

             // --------------------------------------------------------------
            // if company user role is 1 (CORPORATE_MANAGER) show all 
            // company user with same company id
            // --------------------------------------------------------------
            case CompanyUserRole::CORPORATE_MANAGER :
                $company_id = auth()->user()->companies_id;
                $query->whereHas( 'customer.company_user', function( Builder $sale ) use( $company_id ){
                    $sale->where( 'companies_id', $company_id  );
                });
            break;

            // --------------------------------------------------------------
            // if company user role is 2 (TEAM_MANAGER) show all 
            // customer with leader id  in company user team member table
            // customer with leader id  in company user team member table
            // --------------------------------------------------------------
            case CompanyUserRole::TEAM_MANAGER :
                // get all member id
                $memberID = CompanyUserTeam::where('leader_id', auth()->user()->id)->pluck('member_id')->toArray();
                // add leader id
                array_push($memberID, auth()->user()->id);
                $query->whereHas('customer.company_user', function( Builder $sale ) use( $memberID ) {
                    $sale->whereIn( 'id', $memberID  );
                });
            break;

            // --------------------------------------------------------------
            // if company user role is 3 (SALES_STAFF) show all 
            // customer with same company_users_id
            // --------------------------------------------------------------
            case CompanyUserRole::SALES_STAFF :
                $staffID = auth()->user()->id;
                $query ->whereHas('customer.company_user', function( Builder $sale ) use( $staffID ) {
                    $sale->where( 'id', $staffID  );
                });
            break;
        }
        return $query->orderBy('id', 'desc')->take(5)->get();
    }

    public function changeCustomerFlag($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->flag = !$customer->flag;
        if ($customer->flag) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $customer->save();

        return response()->json(['status' => 200, 'flag' => $flag]);
    }

    public function changeCustomerContactUsFlag($id)
    {
        $customerContactUs = CustomerContactUs::findOrFail($id);
        $customerContactUs->flag = !$customerContactUs->flag;
        if ($customerContactUs->flag) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $customerContactUs->save();

        return response()->json(['status' => 200, 'flag' => $flag]);
    }
}
