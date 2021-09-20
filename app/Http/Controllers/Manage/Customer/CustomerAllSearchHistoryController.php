<?php

namespace App\Http\Controllers\Manage\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Helpers\DatatablesHelper;
use Response;
use DataTables;

use App\Models\Customer;
use App\Models\CustomerSearchHistory;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;
use App\Models\CompanyUserTeam;
use Auth;
use Illuminate\Database\Eloquent\Builder;

class CustomerAllSearchHistoryController extends Controller
{
    public function __construct(){
    }

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

    public function show($param)
    {
        //if( $param == 'json' ){
        //    $model = CustomerSearchHistory::with(['customer','customer.company_user','customer.company_user.company']);
        //    return (new DatatablesHelper)->instance($model)->toJson();
        //
        //}
        //abort(404);
    }

    //===================================================
    //A10 SEARCH HISTORY ALL INDEX
    //===================================================
    public function index(){
        $data['page_title']     = __('label.customer_search_history');
        $data['page_type']      = 'detail';

        $data['companies']      = Company::all();

        //======================================================
        //GET company user based on user company roles
        //======================================================
        $user_roles = Auth::user()->company_user_roles_id;
        $user_id = Auth::user()->id;
        $user_company = Auth::user()->companies_id;

        $data['companyuser_options']  =$this->getCompanyUser($user_roles);
        
        //======================================================

        return view('manage.customer.all-search-history.index', $data);
    }

    //===================================================
    //A10 SEARCH HISTORY TABLE LIKE FILTER
    //===================================================
    public function filter(Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $user_roles = Auth::user()->company_user_roles_id;
        $user_company = Auth::user()->companies_id;
        $user_id = Auth::user()->id;

        $relations = collect([ 'customer', 'customer.company_user', 'customer.company_user.company' ]);
        $query = CustomerSearchHistory::select('*');
        $query = $this->loginFilter($query, $user_roles);
      
        // ------------------------------------------------------------------

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // ------------------------------------------------------------------
        // START OTHER filter
        // ------------------------------------------------------------------
        if (!empty($filter->datestart)) {
            $start = Carbon::parse( $filter->datestart );
            $query->whereDate('created_at', '>=', $start );
        }
        if (!empty($filter->datefinish)) {
            $end = Carbon::parse( $filter->datefinish );
            $query->whereDate('created_at', '<=', $end);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Price
        // ------------------------------------------------------------------
        $minPrice = !empty($filter->minprice)?$filter->minprice:'';
        $maxPrice = !empty($filter->maxprice)?$filter->maxprice:'';

        $query = $query->PriceRange($minPrice,$maxPrice);
            
        // ------------------------------------------------------------------
        // Land Area
        // ------------------------------------------------------------------
        $minLandArea = !empty($filter->minland)?$filter->minland:'';
        $maxLandArea = !empty($filter->maxland)?$filter->maxland:'';

        $query = $query->LandAreaRange($minLandArea,$maxLandArea);

        // ------------------------------------------------------------------

        if (!empty($filter->location)) {
            $query->Where('location', 'LIKE', "%{$filter->location}%");
        }

        if (!empty($filter->name)) {
            //$query->Where('customers.name', 'LIKE', "%{$filter->name}%");
            $name = $filter->name;
            $query->whereHas( 'customer', function( Builder $sale ) use( $name ){
                $sale->where( 'name', 'LIKE', "%{$name}%" );
            });
        }

        if (!empty($filter->person)) {
            //$query->where('company_users.id', '=', (int) $filter->person);
            $person = $filter->person;
            $query->whereHas( 'customer.company_user', function( Builder $sale ) use( $person ){
                $sale->where( 'id', '=', (int) $person);
            });
        }

        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        //$orders = ['customer_search_histories.created_at', 'customers.name','company_users.name', 'customer_search_histories.location', 'customer_search_histories.minimum_price', 'customer_search_histories.minimum_land_area'];
        $orders = ['created_at', 'name','company_users_name', 'location', 'minimum_price', 'minimum_land_area'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            $order = null;
            $direction = $filter->direction ?? 'asc';
            $order = $filter->order; // Everything else
            //if ($order) $query = $query->orderBy($order, $direction);

            if($order == 'name'){
                $query = $query->orderBy( 
                    Customer::select( 'name' )
                        ->whereColumn( 'customers.id', 'customer_search_histories.customers_id' ),
                    $direction
                );
            }elseif($order == 'company_users_name'){
                $query = $query->join('customers', 'customers.id', '=', 'customer_search_histories.customers_id')
                                ->join('company_users', 'company_users.id', 'customers.company_users_id')
                                ->orderBy('company_users.name', $direction);
            } elseif( $order ) {
                $query = $query->orderBy( $order, $direction );
            }
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Add the relations, make sure they are unique
        // ------------------------------------------------------------------
        $query->with( $relations->unique()->all());
        // ------------------------------------------------------------------
        
        // ------------------------------------------------------------------
        // Paginate the result
        // ------------------------------------------------------------------
        $paginator = $query->paginate( $perpage, $columns, 'page', $page );
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }
    //==============================================================================
}
