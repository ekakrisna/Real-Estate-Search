<?php

namespace App\Http\Controllers\Manage\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\Customer;
use App\Models\CustomerLogActivity;
use App\Models\CustomerFavoriteProperty;
use App\Models\ActionType;

class CustomerUseHistoryController extends Controller
{
    public function index($id)
    {
        $data['page_title'] = __('label.list_of_usage_history');
        $data['page_type']      = 'detail';
        $data['customer'] = Customer::with('company_user.company')->findOrFail($id);
        $data['actions'] = ActionType::all();

        return view('manage.customer.use_history.index', $data);
    }

    public function filter($id, Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $relations = collect(['customer_favorite_property', 'property', 'action_type']);
        $query = CustomerLogActivity::select('*')
            ->leftjoin('action_types', 'customer_log_activities.action_types_id', '=', 'action_types.id')
            ->leftjoin('properties', 'customer_log_activities.properties_id', '=', 'properties.id')
            ->where('customer_log_activities.customers_id', $id)->orderBy('access_time', 'desc');

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // ------------------------------------------------------------------
        // Minimum access_date filter
        // ------------------------------------------------------------------
        if (!empty($filter->last_use_date_min)) {
            $query->whereDate('access_time', '>=', $filter->last_use_date_min);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum access_date filter
        // ------------------------------------------------------------------
        if (!empty($filter->last_use_date_max)) {
            $query->whereDate('access_time', '<=', $filter->last_use_date_max);
        }
        // ------------------------------------------------------------------
        // action filter
        // ------------------------------------------------------------------
        if (!empty($filter->action)) {
            $query->where('action_types_id', (int) $filter->action);
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
            });
        }

        // ----------------old price and land area filter start------------------//

        // ------------------------------------------------------------------
        // Minimum price filter
        // ------------------------------------------------------------------
        // if( !empty( $filter->priceMin ) ){
        //     $min = fromMan($filter->priceMin);
        //     $query->whereHas( 'property', function( Builder $sale ) use( $min ){
        //         $sale->where( 'minimum_price', '>=', $min  )->orwhere( 'maximum_price', '>=', $priceMin );
        //     });
        // }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // ------------------------------------------------------------------
        // Maximum price filter
        // ------------------------------------------------------------------
        // if( !empty( $filter->priceMax ) ){
        //     $max = fromMan($filter->priceMax);
        //     $query->whereHas( 'property', function( Builder $sale ) use( $max ){
        //         $sale->where( 'maximum_price', '<=', $max  )->orwhere( 'minimum_price', '<=', $priceMax );
        //     });
        // }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Minimum landArea filter
        // ------------------------------------------------------------------
        // if( !empty( $filter->landAreaMin ) ){
        //     $min = fromTsubo($filter->landAreaMin);
        //     $query->whereHas( 'property', function( Builder $sale ) use( $min ){
        //         $sale->where( 'minimum_land_area', '>=', $min  );
        //     });
        // }
        // ------------------------------------------------------------------
        // ------------------------------------------------------------------
        // Maximum landArea filter
        // ------------------------------------------------------------------
        // if( !empty( $filter->landAreaMax ) ){
        //     $max = fromTsubo($filter->landAreaMax);
        //     $query->whereHas( 'property', function( Builder $sale ) use( $max ){
        //         $sale->where( 'maximum_land_area', '<=', $max  );
        //     });
        // }
        // ------------------------------------------------------------------

        // -----------------old price and land area filter end-------------------//


        // ------------------------------------------------------------------
        // Price
        // ------------------------------------------------------------------
        $minPrice = !empty($filter->priceMin) ? $filter->priceMin : '';
        $maxPrice = !empty($filter->priceMax) ? $filter->priceMax : '';
        $query = $query->where(function ($query) use ($minPrice, $maxPrice) {
            $query->whereHas('property', function (Builder $sale) use ($minPrice, $maxPrice) {
                $sale->PriceRange($minPrice, $maxPrice);
            })
                ->orWhere(function ($query) {
                    $query->whereNull('properties_id');
                });
        });

        // ------------------------------------------------------------------
        // Land Area
        // ------------------------------------------------------------------
        $minLandArea = !empty($filter->landAreaMin) ? $filter->landAreaMin : '';
        $maxLandArea = !empty($filter->landAreaMax) ? $filter->landAreaMax : '';
        $query = $query->where(function ($query) use ($minLandArea, $maxLandArea) {
            $query->whereHas('property', function (Builder $sale) use ($minLandArea, $maxLandArea) {
                $sale->LandAreaRange($minLandArea, $maxLandArea);
            })
                ->orWhere(function ($query) {
                    $query->whereNull('properties_id');
                });
        });

        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = ['access_time', 'action_types.label', 'properties_id', 'properties.location', 'properties.minimum_price', 'properties.minimum_land_area', 'properties.building_conditions_desc'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            $order = null;
            $direction = $filter->direction ?? 'asc';
            // --------------------------------------------------------------
            // Order by
            // --------------------------------------------------------------
            $order = $filter->order;
            if ($order) $query = $query->orderBy($order, $direction);
        }

        $query->with($relations->unique()->all());
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
}
