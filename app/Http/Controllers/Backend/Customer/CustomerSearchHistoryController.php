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

class CustomerSearchHistoryController extends Controller
{
    public function __construct()
    {
    }

    //===================================================
    //B8 SEARCH HISTORY TABLE LIKE
    //===================================================
    public function index($id)
    {
        $data['page_title']     = __('label.view_search_history');
        $data['id']             = $id;
        $data['page_type']      = 'detail';

        $data['customer_detail'] = Customer::where('id', $id)->with(['company_user', 'company_user.company'])->first();

        return view('backend.customer.search_history.index', $data);
    }

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
            $start = Carbon::parse($filter->datestart);
            $query = $query->whereDate('created_at', '>=', $start);
        }
        if (!empty($filter->datefinish)) {
            $end = Carbon::parse($filter->datefinish);
            $query = $query->whereDate('created_at', '<=', $end);
        }

        // ------------------------------------------------------------------
        // Price
        // ------------------------------------------------------------------
        $minPrice = !empty($filter->minprice) ? $filter->minprice : '';
        $maxPrice = !empty($filter->maxprice) ? $filter->maxprice : '';

        $query->PriceRange($minPrice, $maxPrice);

        // ------------------------------------------------------------------
        // Land Area
        // ------------------------------------------------------------------
        $minLandArea = !empty($filter->minland) ? $filter->minland : '';
        $maxLandArea = !empty($filter->maxland) ? $filter->maxland : '';

        $query->LandAreaRange($minLandArea, $maxLandArea);

        // ------------------------------------------------------------------
        // Location
        // ------------------------------------------------------------------
        if (!empty($filter->location)) {
            $query->Where('location', 'LIKE', "%{$filter->location}%");
        }

        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = ['created_at', 'location', 'price', 'land_area'];
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
        } else {
            // Default order!
            $query = $query->orderBy('created_at', 'desc');
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
    // ----------------------------------------------------------------------

    //===================================================
    //END B8 SEARCH HISTORY TABLE LIKE
    //===================================================

}
