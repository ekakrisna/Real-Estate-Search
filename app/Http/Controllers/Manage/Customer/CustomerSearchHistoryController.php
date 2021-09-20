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
use Auth;
use Illuminate\Database\Eloquent\Builder;

class CustomerSearchHistoryController extends Controller
{
    public function __construct(){
    }

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
    //A8 SEARCH HISTORY TABLE LIKE INDEX
    //===================================================
    public function index($id)
    {
        $data['page_title']     = __('label.customer_search_history');
        $data['id']             = $id;
        $data['page_type']      = 'detail';

        $data['customer_detail']= Customer::where('id', $id)->with(['company_user', 'company_user.company'])->first();

        return view('manage.customer.search_history.index', $data);
    }
    //==============================================================================

    //===================================================
    //A8 SEARCH HISTORY TABLE LIKE FILTER
    //===================================================
    public function filter($id, Request $request)
    {
        // ------------------------------------------------------------------
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Default configuration
        // ------------------------------------------------------------------
        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Base query
        // ------------------------------------------------------------------
        //$relations = collect([ 'role', 'company.role' ]);
        $query = CustomerSearchHistory::select('*')->where('customers_id', $id);
        // ------------------------------------------------------------------

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        if (!empty($filter->datestart)) {
            $start = Carbon::parse( $filter->datestart );
            $query = $query->whereDate( 'created_at', '>=', $start );
        }
        if (!empty($filter->datefinish)) {
            $end = Carbon::parse( $filter->datefinish );
            $query = $query->whereDate( 'created_at', '<=', $end );
        }

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
                $query->where(function ($query) use ($keywords) {
                    if (!empty($keywords)) foreach ($keywords as $keyword) {
                        $query->orWhere('location', 'LIKE', "%{$keyword}%");
                    }
                });
                // ----------------------------------------------------------
            });
        }


        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = ['created_at', 'location', 'minimum_price', 'minimum_land_area'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            // --------------------------------------------------------------
            $order = null;
            $direction = $filter->direction ?? 'asc';
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Order by 
            // --------------------------------------------------------------
            if ('status' == $filter->order) $order = 'is_active';
            else $order = $filter->order; // Everything else
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            if ($order) $query = $query->orderBy($order, $direction);
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Add the relations, make sure they are unique
        // ------------------------------------------------------------------
        // $query->with( $relations->unique()->all());
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Paginate the result
        // ------------------------------------------------------------------
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }
    //==============================================================================

}
