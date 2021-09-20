<?php

namespace App\Http\Controllers\Backend\Approval;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApprovalListController extends Controller
{
    public function index()
    {
        $data = new \stdClass;
        $data->page_title = __('label.approval');
        return view('backend.approval.index.index', (array) $data);
    }

    public function filter(Request $request)
    {       
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $relations = collect(['property_publish','property_convert_status']);
        $query = Property::select('*')->where([
            ['property_convert_status_id', '!=', 0],
            ['property_convert_status_id', '!=', 999],
            ['property_convert_status_id', '!=', 1000]                        
        ]);
                
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
        $minLandArea = !empty($filter->minland)?toTsubo($filter->minland):'';
        $maxLandArea = !empty($filter->maxland)?toTsubo($filter->maxland):'';

        $query->LandAreaRange($minLandArea,$maxLandArea);

        // ------------------------------------------------------------------

        if (!empty($filter->location)) {
            $query->Where('location', 'LIKE', "%{$filter->location}%");
        }

        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------        
        $orders = ['created_at', 'building_conditions_desc', 'property_convert_status_id','location'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            $order = null;
            $direction = $filter->direction ?? 'asc';
            $order = $filter->order; // Everything else            
            $query = $query->orderBy( $order, $direction );        
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
