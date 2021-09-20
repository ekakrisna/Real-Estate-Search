<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Customer;
use App\Models\CustomerLogActivity;

class CustomerController extends Controller
{
    //===================================================
    //B4 CUSTOMER LIST INDEX
    //===================================================

    public function index(){
        $data['page_title'] = __('label.customer_list');

        $data['companies'] = Company::all();
        $data['company_users'] = CompanyUser::all();

        return view('backend.customer.index.index', $data);
    }

    //===================================================
    //B4 CUSTOMER LIST FILTER
    //===================================================

    public function filter(Request $request)
    {
        // ------------------------------------------------------------------
        $filter = (object) $request->filter;
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------
        
        // ------------------------------------------------------------------
        // Default configuration
        // ------------------------------------------------------------------
        $page = $filter->page ?? 1;
        $perpage = 10; $columns = ['*'];
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Base query
        // ------------------------------------------------------------------
        $relations = collect([ 'customer_log_activities', 'company_user.company', 'customer_last_activity' ]);
        $count_relations = collect(['properties_checked', 'favorite_properties']);
        $query = Customer::select('*');
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // View perpage
        // ------------------------------------------------------------------
        $list = [ 1, 2, 5, 10, 20, 50 ];
        if( !empty( $filter->perpage )){
            $view = (int) $filter->perpage;
            if( in_array( $view, $list )) $perpage = $view;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Minimum customer register date
        // ------------------------------------------------------------------
        if( !empty( $filter->regis_date_min ) ){
            $query->whereDate( 'created_at', '>=', $filter->regis_date_min );
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum customer register date
        // ------------------------------------------------------------------
        if( !empty( $filter->regis_date_max ) ){
            $query->whereDate( 'created_at', '<=', $filter->regis_date_max );
        }
        // ------------------------------------------------------------------

         // ------------------------------------------------------------------
        // if there is customer last use date filter
        // regardless min or max
        // ------------------------------------------------------------------
        if( isset($filter->last_use_date_min) || isset($filter->last_use_date_max)  ){
            $query = $query->join('customer_log_activities', 'customers.id', 'customer_log_activities.customers_id')
            ->where('customer_log_activities.id', function($sale){
                $sale->select('id')
                        ->from('customer_log_activities')
                        ->whereColumn('customers_id','customers.id')
                        ->orderByDesc('access_time')
                        ->limit(1);
            })->select('customers.*', 'customer_log_activities.access_time');
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Minimum customer last use date
        // ------------------------------------------------------------------
        if( !empty( $filter->last_use_date_min ) ){
            $min = $filter->last_use_date_min;
            $query = $query->whereDate('access_time', '>=', $min );
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum customer last use date
        // ------------------------------------------------------------------
        if( !empty( $filter->last_use_date_max ) ){
            $max = $filter->last_use_date_max;
            $query = $query->whereDate('access_time', '<=', $max);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // license status filter
        // ------------------------------------------------------------------
        if( !empty( $filter->license)){
            if($filter->license == '2') {
                $query->where( 'license', 0 );
            } else {
                $query->where( 'license', (int) $filter->license );
            }
        }
        // ------------------------------------------------------------------

        // company status filter
        // ------------------------------------------------------------------
        if( !empty( $filter->corporate)){
            $companyID = (int) $filter->corporate;
            $query->whereHas( 'company_user', function( Builder $sale ) use( $companyID ){
                $sale->where( 'companies_id', $companyID  );
            });
        }
        // ------------------------------------------------------------------
        
        // company user status filter
        // ------------------------------------------------------------------
        if( !empty( $filter->person)){
            $query->where( 'company_users_id', (int) $filter->person );
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // General search
        // ------------------------------------------------------------------
        if( !empty( $filter->name )){
            // --------------------------------------------------------------
            $query->where( function( $query ) use( $filter ){
                // ----------------------------------------------------------
                $keywords = preg_replace( '/\s+/', ' ', $filter->name );
                $keywords = explode( ' ', trim( $keywords ));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Search user
                // ----------------------------------------------------------
                $query->where( function( $query ) use( $keywords ){
                    if( !empty( $keywords )) foreach( $keywords as $keyword ){
                        $query->orWhere( 'name', 'LIKE', "%{$keyword}%" );
                    }
                });
                // ----------------------------------------------------------
            });
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = [ 'name', 'created_at', 'favorite_properties', 'properties_checked', 'license', 'flag', 'company_users_id', 'customer_last_activity', 'company'];
        if( !empty( $filter->order ) && in_array( $filter->order, $orders )){
            // --------------------------------------------------------------
            $order = null;
            $direction = $filter->direction ?? 'asc';
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Order by 
            // --------------------------------------------------------------
            $order = $filter->order; // Everything else
            // --------------------------------------------------------------

            if($order == 'favorite_properties'){
                $query = $query->withCount('favorite_properties')
                                ->orderBy('favorite_properties_count', $direction);
            } elseif($order == 'properties_checked'){
                $query = $query->withCount('properties_checked')
                                ->orderBy('properties_checked_count', $direction);
            } elseif($order == 'company_users_id'){
                $query = $query->orderBy( 
                    CompanyUser::select( 'name' )
                        ->whereColumn( 'company_users.id', 'customers.company_users_id' ),
                    $direction
                );
            } elseif($order == 'customer_last_activity'){
                $query = $query->orderBy(CustomerLogActivity::select('access_time')
                                                ->whereColumn('customer_log_activities.customers_id', 'customers.id')
                                                ->latest()
                                                ->take(1)
                                                , $direction);
            } elseif($order == 'company'){
                $query = $query->join('company_users', 'customers.company_users_id', '=', 'company_users.id')
                                ->join('companies', 'company_users.companies_id', 'companies.id')
                                ->select('customers.*')
                                ->orderBy('companies.company_name', $direction);
            } elseif( $order ) {
                $query = $query->orderBy( $order, $direction );
            }
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        

        // ------------------------------------------------------------------
        // Add the relations, make sure they are unique
        // ------------------------------------------------------------------
        $query->withCount('properties_checked')->withCount('favorite_properties')->with( $relations->unique()->all());
        // ------------------------------------------------------------------
        
        // ------------------------------------------------------------------
        // Paginate the result
        // ------------------------------------------------------------------
        $paginator = $query->paginate( $perpage, $columns, 'page', $page );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $response->result = $paginator;
        $response->filter = $filter;
        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        // ------------------------------------------------------------------
    }
}
