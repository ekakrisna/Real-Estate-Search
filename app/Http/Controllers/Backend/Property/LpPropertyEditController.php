<?php

namespace App\Http\Controllers\Backend\Property;

use App\Http\Controllers\Controller;
use App\Models\LpProperty;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LpPropertyEditController extends Controller
{
    public function index($id)
    {
        // ------------------------------------------------------------------
        $data = new \stdClass;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Page meta
        // ------------------------------------------------------------------
        $data->page_type = 'edit';
        $data->page_title = __('label.corection_property');
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Property
        // ------------------------------------------------------------------
        $relations = [
            'property_status',
        ];
        $data->id = $id;
        $data->property = LpProperty::with($relations)->find($id);
        if (!$data->property) {
            return abort(404);
        }

        // ------------------------------------------------------------------
        return view('backend.lp.property.edit.index', (array) $data);
        // ------------------------------------------------------------------
    }

    public function update(Request $request, $id)
    {
        // --------------------------------------------------------------
        // get property data from request
        // --------------------------------------------------------------
        $dataset = json_decode($request->dataset, true);
        $property_data = $dataset['property'];
        $property = LpProperty::findOrFail($id);        
        if ($property->lp_property_status_id == 4) {
            if ($property_data['lp_property_status_id'] == 2) {
                $property_data['publish_date'] = Carbon::now();
            }
        }
        $property->update($property_data);

        // --------------------------------------------------------------
        // return response
        // --------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        $response->property = $property;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // --------------------------------------------------------------
    }
}
