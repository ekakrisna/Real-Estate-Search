<?php

namespace App\Http\Controllers\Manage\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Customer;
use App\Models\CompanyUser;
use App\Models\CustomerLogActivity;
use App\Models\CustomerFavoriteProperty;
use App\Models\ActionType;
use App\Models\CompanyUserTeam;
use App\Models\CompanyUserRole;

class CustomerAllUseHistoryController extends Controller
{
    //===================================================
    // private function to get company user list
    // from current logged in company user role id
    // as a parameter
    //===================================================
    private function getCompanyUser($company_user_role)
    {
        switch($company_user_role)
        {
            //===================================================
            // Get company user list from the same company
            //===================================================
            case CompanyUserRole::CORPORATE_MANAGER :
                $companyID = auth()->user()->companies_id;
                $query = CompanyUser::where('companies_id', $companyID )->get();
            break;
            //===================================================

            //===================================================
            // Get company user list from the same company leader
            // with the leader itself
            //===================================================
            case CompanyUserRole::TEAM_MANAGER :
                $companyUserID = auth()->user()->id;
                $query = CompanyUser::whereHas( 'company_user_teams', function( Builder $sale ) use( $companyUserID ){
                    $sale->where( 'leader_id', $companyUserID  );
                })->orWhere('id', $companyUserID)->get();
            break;
            //===================================================

            //===================================================
            // Get company user list from the same company user id (1 list only)
            //===================================================
            case CompanyUserRole::SALES_STAFF :
                $companyUserID = auth()->user()->id;
                $query = CompanyUser::where('id', $companyUserID)->get();
            break;
            //===================================================
        }
        return $query;
    }
    //===================================================

    //===================================================
    // A11 CUSTOMER ALL USE HISTORY LIST INDEX
    //===================================================
    public function index ()
    {
        $company_user_role_id   = auth()->user()->company_user_roles_id;

        $data['page_title']     = __('label.history_customer_search');
        $data['page_type']      = 'detail';

        $data['actions'] = ActionType::all();
        $data['company_users']  = $this->getCompanyUser($company_user_role_id);

        return view('manage.customer.customer_all_use_history.index', $data);
    }
    //===================================================

    //===================================================
    // private function to get customer user list
    // from current logged in company user role id
    // as a parameter
    //===================================================
    private function loginFilter($query, $company_user_role)
    {
        switch($company_user_role)
        {
            //===================================================
            // Get company user list from the same company
            //===================================================
            case CompanyUserRole::CORPORATE_MANAGER :
                $companyID = auth()->user()->companies_id;
                $query = $query->whereHas( 'customer.company_user', function( Builder $sale ) use( $companyID ){
                    $sale->where( 'companies_id', $companyID  );
                });
            break;
            //===================================================
            // Get company user list from the same company leader
            // with the leader itself
            //===================================================
            case CompanyUserRole::TEAM_MANAGER :
                $companyUserID = auth()->user()->id;
                $memberID = CompanyUserTeam::where('leader_id', auth()->user()->id)->pluck('member_id')->toArray();
                // add leader id
                $memberID[] = $companyUserID;
                $query = $query->whereHas('customer.company_user', function( Builder $sale ) use( $memberID ) {
                    $sale->whereIn( 'id', $memberID  );
                });
            break;
            //===================================================
            // Get company user list from the same company user id (1 list only)
            //===================================================
            case CompanyUserRole::SALES_STAFF :
                $companyUserID = auth()->user()->id;
                $query = $query->whereHas('customer.company_user', function( Builder $sale ) use( $companyUserID ) {
                    $sale->where( 'id', $companyUserID  );
                });
            break;
        }
        return $query;
    }
    //===================================================

    //===================================================
    // A11 CUSTOMER ALL USE HISTORY LIST FILTER
    //===================================================
    public function filter(Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $relations = collect(['customer_favorite_property', 'property', 'action_type', 'customer.company_user.company']);
        $query = CustomerLogActivity::select('*')
            ->leftjoin('action_types', 'customer_log_activities.action_types_id', '=', 'action_types.id')
            ->leftjoin('properties', 'customer_log_activities.properties_id', '=', 'properties.id')
            ->orderBy('access_time', 'desc');

        // ------------------------------------------------------------------
        // Filter customer list based on company user role id ( from logged in company user )
        // ------------------------------------------------------------------
        $company_user_role_id = auth()->user()->company_user_roles_id;
        
        $query = $this->loginFilter($query, $company_user_role_id);
        // ------------------------------------------------------------------

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // ------------------------------------------------------------------
        // access_time minimum filter
        // ------------------------------------------------------------------
        if( !empty( $filter->last_use_date_min)){
            $query->whereDate('access_time', '>=', $filter->last_use_date_min);
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // access_time maximum filter
        // ------------------------------------------------------------------
        if( !empty( $filter->last_use_date_max)){
            $query->whereDate('access_time', '<=', $filter->last_use_date_max);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // company user filter
        // ------------------------------------------------------------------
        if( !empty( $filter->company_user )){
            $companyUserID = (int) $filter->company_user;
            $query->whereHas( 'customer.company_user', function( Builder $sale ) use( $companyUserID ){
                $sale->where( 'id', $companyUserID );
            });
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // action filter
        // ------------------------------------------------------------------
        if( !empty( $filter->action)){
            $query->where( 'action_types_id', (int) $filter->action );
        }
        // search location
        // ------------------------------------------------------------------
        if (!empty($filter->location)) {
            // --------------------------------------------------------------
            $query->where(function ($query) use ($filter) {
                // ----------------------------------------------------------
                $keywords = preg_replace('/\s+/', ' ', $filter->location);
                $keywords = explode(' ', trim($keywords));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Search location
                // ----------------------------------------------------------
                $query->whereHas('property', function (Builder $query) use ($keywords) {
                    $query->where(function ($query) use ($keywords) {
                        if (!empty($keywords)) foreach ($keywords as $keyword) {
                            $query->orWhere('location', 'LIKE', "%{$keyword}%");
                        }
                    });
                });
                // ----------------------------------------------------------
                // ----------------------------------------------------------
                // customer  name location
                // ----------------------------------------------------------
                $query->orWhereHas('customer', function (Builder $query) use ($keywords) {
                    $query->where(function ($query) use ($keywords) {
                        if (!empty($keywords)) foreach ($keywords as $keyword) {
                            $query->orWhere('name', 'LIKE', "%{$keyword}%");
                        }
                    });
                });
                // ----------------------------------------------------------
            });
        }
        // search location
        // ------------------------------------------------------------------
        if (!empty($filter->name)) {
            // --------------------------------------------------------------
            $query->where(function ($query) use ($filter) {
                // ----------------------------------------------------------
                $keywords = preg_replace('/\s+/', ' ', $filter->name);
                $keywords = explode(' ', trim($keywords));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // customer  name location
                // ----------------------------------------------------------
                $query->orWhereHas('customer', function (Builder $query) use ($keywords) {
                    $query->where(function ($query) use ($keywords) {
                        if (!empty($keywords)) foreach ($keywords as $keyword) {
                            $query->orWhere('name', 'LIKE', "%{$keyword}%");
                        }
                    });
                });
                // ----------------------------------------------------------
            });
        }

        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = ['access_time', 'name', 'in_charge_staff', 'action_types.label', 'properties_id', 'properties.location', 'properties.building_conditions_desc'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            $order = null;
            $direction = $filter->direction ?? 'asc';
            // --------------------------------------------------------------
            // Order by 
            // --------------------------------------------------------------
            $order = $filter->order;
            // --------------------------------------------------------------
            // Relation based order
            // --------------------------------------------------------------
            $relationBasedOrders = [ 'name', 'in_charge_staff' ];
            if( in_array( $filter->order, $relationBasedOrders )){
                // ----------------------------------------------------------
                // Order user by the customer name table
                // ----------------------------------------------------------
                if( 'name' === $filter->order ){
                    $query->orderBy( 
                        Customer::select( 'name' )
                            ->whereColumn( 'customers.id', 'customer_log_activities.customers_id' ),
                        $direction
                    );
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Order user by the company user name table
                // ----------------------------------------------------------
                elseif( 'in_charge_staff' === $filter->order ){
                    $query = $query->join('customers', 'customer_log_activities.customers_id', '=', 'customers.id')
                                ->join('company_users', 'customers.company_users_id', 'company_users.id')
                                ->select('customer_log_activities.*')
                                ->orderBy('company_users.name', $direction);
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Regular order
            // --------------------------------------------------------------
            else if( $order ) $query = $query->orderBy( $order, $direction );
            // --------------------------------------------------------------
        }

        $query->with($relations->unique()->all());
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
    //===================================================
}
