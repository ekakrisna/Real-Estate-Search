<?php

namespace App\Http\Controllers\Backend\Property;

use App\Http\Controllers\Controller;
use App\Models\LpProperty;
use App\Models\LpPropertyStatus;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LpPropertyController extends Controller
{
    public function index()
    {
        $data['page_title'] = __('label.lp_list_property');
        $data['property_statuses'] = LpPropertyStatus::all();

        return view('backend.lp.property.index.index', $data);
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Property list filter
    // ----------------------------------------------------------------------
    public function filter(Request $request)
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
        $relations = collect(['property_status', 'property_publish']);
        $query = LpProperty::select('*');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // View perpage
        // ------------------------------------------------------------------
        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Minimum property update date
        // ------------------------------------------------------------------
        if (!empty($filter->updateDateStart)) {
            $query->whereDate('updated_at', '>=', $filter->updateDateStart);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum property update date
        // ------------------------------------------------------------------
        if (!empty($filter->updateDateEnd)) {
            $query->whereDate('updated_at', '<=', $filter->updateDateEnd);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Minimum property contracted date
        // ------------------------------------------------------------------
        if (!empty($filter->contractDateStart)) {
            $query->whereDate('contracted_years', '>=', $filter->contractDateStart);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum property contracted date
        // ------------------------------------------------------------------
        if (!empty($filter->contractDateEnd)) {
            $query->whereDate('contracted_years', '<=', $filter->contractDateEnd);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // property ID
        // ------------------------------------------------------------------
        if (!empty($filter->id)) {
            $id = $filter->id;
            $query->whereHas('property_publish',function($q)use($id){
                $q->where('property_number','like','%'.$id.'%');               
            });
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Price
        // ------------------------------------------------------------------
        $minPrice = !empty($filter->priceMin) ? $filter->priceMin : '';
        $maxPrice = !empty($filter->priceMax) ? $filter->priceMax : '';
        $query->PriceRange($minPrice, $maxPrice);

        // ------------------------------------------------------------------
        // Land Area
        // ------------------------------------------------------------------
        $minLandArea = !empty($filter->landAreaMin) ? $filter->landAreaMin : '';
        $maxLandArea = !empty($filter->landAreaMax) ? $filter->landAreaMax : '';
        $query->LandAreaRange($minLandArea, $maxLandArea);

        // ------------------------------------------------------------------
        // locaiton search
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


                // ----------------------------------------------------------
            });
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Minimum building area
        // ------------------------------------------------------------------
        if (!empty($filter->BuildingAreaMin)) {
            $buildingAreaMin = fromTsubo($filter->BuildingAreaMin);
            $query->where('building_area', '>=', $buildingAreaMin);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum building area
        // ------------------------------------------------------------------
        if (!empty($filter->BuildingAreaMax)) {
            $buildingAreaMax = fromTsubo($filter->BuildingAreaMax);
            $query->where('building_area', '<=', $buildingAreaMax);
        }
        // ------------------------------------------------------------------
        // ------------------------------------------------------------------
        // property building conditions
        // ------------------------------------------------------------------
        if (!empty($filter->propertyStatus)) {
            $query->where('lp_property_status_id', '=', (int) $filter->propertyStatus);
        }
        // ------------------------------------------------------------------
        // ------------------------------------------------------------------
        // Minimum building age
        // ------------------------------------------------------------------
        if (!empty($filter->BuildingAgeMin)) {
            $query->where('building_age', '>=', $filter->BuildingAgeMin);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum building age
        // ------------------------------------------------------------------
        if (!empty($filter->BuildingAgeMax)) {
            $query->where('building_age', '<=', $filter->BuildingAgeMax);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // house layout search
        // ------------------------------------------------------------------
        if (!empty($filter->house_layout)) {
            // --------------------------------------------------------------
            $query->where(function ($query) use ($filter) {
                // ----------------------------------------------------------
                $keywords = preg_replace('/\s+/', ' ', $filter->house_layout);
                $keywords = explode(' ', trim($keywords));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Search house_layout
                // ----------------------------------------------------------
                $query->where(function ($query) use ($keywords) {
                    if (!empty($keywords)) foreach ($keywords as $keyword) {
                        $query->orWhere('house_layout', 'LIKE', "%{$keyword}%");
                    }
                });
                // ----------------------------------------------------------


                // ----------------------------------------------------------
            });
        }
        // ------------------------------------------------------------------
        // ------------------------------------------------------------------
        // Connecting Road search
        // ------------------------------------------------------------------
        if (!empty($filter->connecting_road)) {
            // --------------------------------------------------------------
            $query->where(function ($query) use ($filter) {
                // ----------------------------------------------------------
                $keywords = preg_replace('/\s+/', ' ', $filter->connecting_road);
                $keywords = explode(' ', trim($keywords));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Search connecting_road
                // ----------------------------------------------------------
                $query->where(function ($query) use ($keywords) {
                    if (!empty($keywords)) foreach ($keywords as $keyword) {
                        $query->orWhere('connecting_road', 'LIKE', "%{$keyword}%");
                    }
                });
                // ----------------------------------------------------------


                // ----------------------------------------------------------
            });
        }
        // ------------------------------------------------------------------
        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = [
            'updated_at', 'contracted_years', 'id', 'location', 'minimum_price', 'minimum_land_area',
            'property_status', 'building_area', 'building_age', 'house_layout', 'connecting_road',
        ];

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
            // Relation based order
            // --------------------------------------------------------------
            $relationBasedOrders = ['property_status'];
            if (in_array($filter->order, $relationBasedOrders)) {
                // ----------------------------------------------------------
                // Order user by the PropertyStatus table
                // ----------------------------------------------------------
                if ('property_status' === $filter->order) {
                    $query->orderBy(
                        LpPropertyStatus::select('label')
                            ->whereColumn('lp_property_statuses.id', 'lp_properties.lp_property_status_id'),
                        $direction
                    );
                }
                // ----------------------------------------------------------

            }
            // --------------------------------------------------------------
            // --------------------------------------------------------------
            // Regular order
            // --------------------------------------------------------------
            else if ($order) $query = $query->orderBy($order, $direction);
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------
        // ------------------------------------------------------------------
        // Query debugging
        // ------------------------------------------------------------------
        // dd( $query->toSql(), $query->getBindings());
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Add the relations, make sure they are unique
        // ------------------------------------------------------------------
        $query->with($relations->unique()->all());
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
}
