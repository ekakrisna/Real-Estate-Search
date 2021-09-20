<?php

namespace App\Http\Controllers\Backend\Approval;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyPublish;
use App\Models\PropertyStatus;
use App\Models\PropertyConvertStatus;
use Illuminate\Http\Request;
use App\Console\Scraping\DataRow\Base\BaseScrapingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ApprovalEditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        $property = Property::find($id);
        if ($property === null) {
            return;
        }

        $data = new \stdClass;
        // ------------------------------------------------------------------
        $data->page_type    = "edit";
        $data->page_title   = __('label.approval_edit');
        $data->id           =  $id;
        $data->available    = __('label.yes');
        $data->none         = __('label.none');
        // ------------------------------------------------------------------

        $data->property         = Property::with(['property_publish', 'property_convert_status'])->where('id', $id)->first();
        $propertyPublishEmpty   = factory(PropertyPublish::class)->state('init')->make();
        $propertyPublish        = PropertyPublish::where('properties_id', $id)->get();

        $dataMinMax = new \stdClass;
        $dataMinMax->minimum_price = $data->property->minimum_price === null ? null : toMan($data->property->minimum_price);
        $dataMinMax->maximum_price = $data->property->maximum_price === null ? null : toMan($data->property->maximum_price);
        $dataMinMax->minimum_land_area = $data->property->minimum_land_area === null ? null : $data->property->minimum_land_area;
        $dataMinMax->maximum_land_area = $data->property->maximum_land_area === null ? null : $data->property->maximum_land_area;

        $data->priceland = $dataMinMax;

        $area = new \stdClass;
        $area->propertyPublish = $propertyPublish;
        $data->publish = $area;

        $template = new \stdClass;
        $template->propertyPublish = $propertyPublishEmpty;
        $data->template = $template;


        return view('backend.approval.edit.index', (array) $data);
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
        try {

            // check location
            $afterLocation = BaseScrapingRow::convartLocation($request['location']);
            $location = "";
            if ($afterLocation['IS_FULL_NAME_MATCH']) {
                $location = $afterLocation['FULL_NAME'];
            }

            if ($location == "") {
                $response = new \stdClass;
                $response->status = 'wrong_location';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }

            $response = new \stdClass;
            $property = Property::findOrFail($id);
            $data['property_statuses_id']       = PropertyStatus::PUBLISHED;
            $data['location']                   = $location;
            $data['minimum_price']              = fromMan($request['minimum_price']);
            $data['maximum_price']              = fromMan($request['maximum_price']);
            $data['minimum_land_area']          = $request['minimum_land_area'];
            $data['maximum_land_area']          = $request['maximum_land_area'];
            $data['property_convert_status_id'] = PropertyConvertStatus::ALRADY_UPDATE;
            $data['town_id']                    = $afterLocation['TOWN_ID'];
            //$data['building_conditions']        = $request['building_conditions'];
            //$data['building_conditions_desc']   = $request['building_conditions_desc'];
            $property->update($data);

            $propertyPublish                    = $request['propertyPublish'];

            $item = PropertyPublish::where('properties_id', $id);
            $item->delete();

            if (!empty($propertyPublish)) {
                foreach ($propertyPublish as $group => $value) {
                    $publish['properties_id']           = $id;
                    $publish['publication_destination'] = $value['publication_destination'];
                    $publish['url']                     = $value['url'];
                    PropertyPublish::create((array) $publish);
                    $response->status = 'success';
                }
            }
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        } catch (\Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Backend/ApprovalEditController (update Function), Error: ", $e);
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
