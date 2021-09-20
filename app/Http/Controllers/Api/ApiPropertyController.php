<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BeforeLoginCustomerLoginLogActivities;
use App\Models\BeforeLoginCustomers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// --------------------------------------------------------------------------
use App\Models\PropertyPhoto;
use App\Models\PropertyFlyer;
use App\Models\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ApiPropertyController extends Controller
{
    public function deletePhotos(Request $request)
    {
        $data = $request->all();
        // ------------------------------------------------------------------
        // array of file id from selected photos
        // ------------------------------------------------------------------
        $property_photo_files = PropertyPhoto::whereIn('id', $data['items'])->pluck('file_id')->toArray();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // delete all property photos from selected photos
        // ------------------------------------------------------------------
        PropertyPhoto::whereIn('id', $data['items'])->delete();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get all files from selected photos
        // ------------------------------------------------------------------
        $files = File::whereIn('id', $property_photo_files)->get();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // for each files, delete file in db and storage
        // ------------------------------------------------------------------
        foreach ($files as $item) {
            $file_name = 'properties/' . $item->name . '.' . $item->extension;
            if (Storage::exists($file_name) && !empty($file_name)) {
                Storage::delete($file_name);
            }

            $item->delete($item->id);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get all remaining property photos of the property id after delete selected photo
        // ------------------------------------------------------------------
        $property_photos = PropertyPhoto::with('file')->where('properties_id', $data['property_id'])->get();
        $response = $property_photos;
        return response()->json($response);
        // ------------------------------------------------------------------
    }

    public function deleteFlyers(Request $request)
    {
        $data = $request->all();
        // ------------------------------------------------------------------
        // array of file id from selected photos
        // ------------------------------------------------------------------
        $property_flyer_files = PropertyFlyer::whereIn('id', $data['items'])->pluck('file_id')->toArray();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // delete all property photos from selected photos
        // ------------------------------------------------------------------
        PropertyFlyer::whereIn('id', $data['items'])->delete();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get all files from selected photos
        // ------------------------------------------------------------------
        $files = File::whereIn('id', $property_flyer_files)->get();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // for each files, delete file in db and storage
        // ------------------------------------------------------------------
        foreach ($files as $item) {
            $file_name = 'properties/' . $item->name . '.' . $item->extension;
            if (Storage::exists($file_name) && !empty($file_name)) {
                Storage::delete($file_name);
            }

            $item->delete($item->id);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get all remaining property flyers of the property id after delete selected photo
        // ------------------------------------------------------------------
        $property_flyers = PropertyFlyer::with('file')->where('properties_id', $data['property_id'])->get();
        $response = $property_flyers;
        return response()->json($response);
        // ------------------------------------------------------------------
    }

    public function deviceUuid(Request $request, $id)
    {
        try {
            $macAddress = BeforeLoginCustomers::where('uuid', $request->uuid)->first();
            if (!$macAddress) {
                $newMacAddress = new BeforeLoginCustomers;
                $newMacAddress->uuid = $request->uuid;
                $newMacAddress->created_at = Carbon::now();
                $newMacAddress->updated_at = Carbon::now();
                $newMacAddress->save();
                if ($newMacAddress) {
                    $activitiesBeforeLogin = BeforeLoginCustomerLoginLogActivities::where([['uuid', $request->uuid], ['properties_id', $id]])->first();
                    if (!$activitiesBeforeLogin) {
                        $newActivitiesBeforeLogin = new BeforeLoginCustomerLoginLogActivities;
                        $newActivitiesBeforeLogin->properties_id = $id;
                        $newActivitiesBeforeLogin->uuid = $request->uuid;
                        $newActivitiesBeforeLogin->access_time = Carbon::now();
                        $newActivitiesBeforeLogin->before_login_customers_id = $newMacAddress->id;
                        $newActivitiesBeforeLogin->save();
                        if ($newActivitiesBeforeLogin) {
                            $response = 'success';
                        }
                    } else {
                        $activitiesBeforeLogin->properties_id = $id;
                        $activitiesBeforeLogin->uuid = $request->uuid;
                        $activitiesBeforeLogin->access_time = Carbon::now();
                        $activitiesBeforeLogin->before_login_customers_id = $newMacAddress->id;
                        $activitiesBeforeLogin->save();
                        if ($activitiesBeforeLogin) {
                            $response = 'success';
                        }
                    }
                }
            } else {
                $macAddress->uuid = $request->uuid;
                $macAddress->updated_at = Carbon::now();
                $macAddress->save();
                if ($macAddress) {
                    $activitiesBeforeLogin = BeforeLoginCustomerLoginLogActivities::where([['uuid', $request->uuid], ['properties_id', $id]])->first();
                    if (!$activitiesBeforeLogin) {
                        $newActivitiesBeforeLogin = new BeforeLoginCustomerLoginLogActivities;
                        $newActivitiesBeforeLogin->properties_id = $id;
                        $newActivitiesBeforeLogin->uuid = $request->uuid;
                        $newActivitiesBeforeLogin->access_time = Carbon::now();
                        $newActivitiesBeforeLogin->before_login_customers_id = $macAddress->id;
                        $newActivitiesBeforeLogin->save();
                        if ($newActivitiesBeforeLogin) {
                            $response = 'success';
                        }
                    } else {
                        $activitiesBeforeLogin->properties_id = $id;
                        $activitiesBeforeLogin->uuid = $request->uuid;
                        $activitiesBeforeLogin->access_time = Carbon::now();
                        $activitiesBeforeLogin->before_login_customers_id = $macAddress->id;
                        $activitiesBeforeLogin->save();
                        if ($activitiesBeforeLogin) {
                            $response = 'success';
                        }
                    }
                }
            }

            return response()->json($response);
        } catch (\Throwable $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("ApiPropertyController (deviceUuid Function), Error: ", $e);
            //------------------------------------------------------

            throw $e;
        }
        // ------------------------------------------------------------------
    }

    public function checkUuidProperty(Request $request)
    {
        $beforeLogin = BeforeLoginCustomerLoginLogActivities::where([
            ['properties_id', '=', $request->id_property],
            ['uuid', '=',  $request->uuid],
        ])->first();
        if ($beforeLogin == null) {
            $response = false;
            return response()->json($response);
        } else {
            $response = true;
            return response()->json($response);
        }
    }
}
