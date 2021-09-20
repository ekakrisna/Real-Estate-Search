<?php

namespace App\Http\Controllers\Backend\Customer;

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
use Illuminate\Database\Eloquent\Builder;

class CustomerAllSearchHistoryController extends Controller
{
    
    //===================================================
    //B10 SEARCH HISTORY ALL
    //===================================================
    public function index(){
        $data['page_title']     = __('label.customer_search_history');
        $data['page_type']      = 'detail';

        return view('backend.customer.all_search_history.index', $data);
    }

    public function filter(Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $relations = collect([ 'customer', 'customer.company_user', 'customer.company_user.company' ]);
        $query = CustomerSearchHistory::select('*');

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

         $query->PriceRange($minPrice,$maxPrice);
            
        // ------------------------------------------------------------------
        // Land Area
        // ------------------------------------------------------------------
        $minLandArea = !empty($filter->minland)?$filter->minland:'';
        $maxLandArea = !empty($filter->maxland)?$filter->maxland:'';

        $query->LandAreaRange($minLandArea,$maxLandArea);

        // ------------------------------------------------------------------

        if (!empty($filter->location)) {
            $query->Where('location', 'LIKE', "%{$filter->location}%");
        }

        if (!empty($filter->name)) {
            $name = $filter->name;
            $query->whereHas( 'customer', function( Builder $sale ) use( $name ){
                $sale->where( 'name', 'LIKE', "%{$name}%" );
            });
        }

        
        if (!empty($filter->person)) {
            $personID = (int) $filter->person;
            $query->whereHas('customer.company_user', function (Builder $sale) use ($personID) {
                $sale->where('id', $personID);
            });
        }

         if (!empty($filter->corporate)) {
            //$query->where('company_users.id', '=', (int) $filter->person);
            $corporateID = (int) $filter->corporate;
            $query->whereHas('customer.company_user.company', function (Builder $sale) use ($corporateID) {
                $sale->where('id', $corporateID);
            });
        }

        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        //$orders = ['customer_search_histories.created_at', 'customers.name','company_users.name', 'customer_search_histories.location', 'customer_search_histories.minimum_price', 'customer_search_histories.minimum_land_area'];
        $orders = ['created_at', 'name', 'company_name','company_users_name', 'location', 'minimum_price', 'minimum_land_area'];
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

            }elseif($order == 'company_name'){
                $query = $query->join('customers', 'customers.id', '=', 'customer_search_histories.customers_id')
                                ->join('company_users', 'company_users.id', 'customers.company_users_id')
                                ->join('companies', 'companies.id', 'company_users.companies_id')
                                ->orderBy('companies.company_name', $direction);
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
}
