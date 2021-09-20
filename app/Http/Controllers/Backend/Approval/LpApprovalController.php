<?php

namespace App\Http\Controllers\Backend\Approval;

use App\Http\Controllers\Controller;
use App\Models\LpProperty;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LpApprovalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = new \stdClass;
        $data->page_title = __('label.lp_list_approval');
        return view('backend.lp.approval.index.index', (array) $data);
    }

    public function filter(Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $relations = collect(['property_convert_status']);
        $query = LpProperty::select('*')->where([
            ['lp_property_convert_status_id', '!=', 1],
            ['lp_property_convert_status_id', '!=', 999],
            ['lp_property_convert_status_id', '!=', 0],
        ]);

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

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
        // Price
        // ------------------------------------------------------------------
        $minPrice = !empty($filter->minprice) ? $filter->minprice : '';
        $maxPrice = !empty($filter->maxprice) ? $filter->maxprice : '';
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
            'updated_at', 'contracted_years', 'location', 'minimum_price', 'minimum_land_area',
            'building_area', 'building_age', 'house_layout', 'connecting_road',
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
            $query->orderBy($order, $direction);
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
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------      
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
