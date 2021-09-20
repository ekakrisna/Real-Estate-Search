<?php

namespace App\Http\Controllers\Backend\Approval;

use App\Http\Controllers\Controller;
use App\Models\LpProperty;
use App\Models\LpPropertyConvertStatus;
use App\Models\LpPropertyStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LpApprovalEditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $property = LpProperty::find($id);
        if ($property === null) {
            return;
        }

        $data = new \stdClass;
        // ------------------------------------------------------------------
        $data->page_type    = "edit";
        $data->page_title   = __('label.corection_property');
        $data->id           =  $id;
        $data->available    = __('label.yes');
        $data->none         = __('label.none');
        // ------------------------------------------------------------------

        $data->property         = LpProperty::with(['property_convert_status'])->where('id', $id)->first();

        $dataMinMax = new \stdClass;
        $dataMinMax->minimum_price = $data->property->minimum_price === null ? null : toMan($data->property->minimum_price);
        $dataMinMax->maximum_price = $data->property->maximum_price === null ? null : toMan($data->property->maximum_price);
        $dataMinMax->minimum_land_area = $data->property->minimum_land_area === null ? null : $data->property->minimum_land_area;
        $dataMinMax->maximum_land_area = $data->property->maximum_land_area === null ? null : $data->property->maximum_land_area;

        $data->priceland = $dataMinMax;

        return view('backend.lp.approval.edit.index', (array) $data);
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
        try {
            $response = new \stdClass;
            $property = LpProperty::findOrFail($id);
            $data['lp_property_status_id']      = LpPropertyStatus::PUBLISHED;
            $data['location']                   = $request['location'];
            $data['minimum_price']              = fromMan($request['minimum_price']);
            $data['maximum_price']              = fromMan($request['maximum_price']) == "" ? null : fromMan($request['maximum_price']);
            $data['minimum_land_area']          = $request['minimum_land_area'];
            $data['maximum_land_area']          = $request['maximum_land_area'];
            $data['lp_property_convert_status_id'] = LpPropertyConvertStatus::ALRADY_UPDATE;

            if ($property->update($data)) {
                $response->property = $property;
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Backend/LpApprovalEditController (update Function), Error: ", $e);
            //------------------------------------------------------
            throw $e;
        }
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
