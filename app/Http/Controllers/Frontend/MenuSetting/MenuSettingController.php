<?php

namespace App\Http\Controllers\Frontend\MenuSetting;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerDesiredArea;
use App\Models\CustomerLogActivity;
use App\Models\ActionType;
use App\Models\CitiesAreas;
use App\Models\City;
use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;
use App\Models\Town;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MenuSettingController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'prefectures' => 'required',
            'city'       => 'required',
        ]);
    }
    protected function validatorCustomer(array $data)
    {
        return Validator::make($data, [
            'minimum_price'             => 'required|numeric',
            'maximum_price'             => 'required|numeric',
        ]);
    }

    public function index()
    {
        $data = new \stdClass;
        $id = Auth::guard('user')->user()->id;
        $data->customer_desired_areas = CustomerDesiredArea::with('city.prefecture', 'city_areas')->where('customers_id', $id)->get();
        $data->consider_amount        = ListConsiderAmount::get();
        $data->land_area              = ListLandArea::get();
        $data->customer               = Customer::where('id', $id)->first();
        $data->title                  = __('label.MySetting');
        return view('frontend.setting_search.index', (array) $data);
    }

    public function remove(Request $request)
    {
        try {
            $data = $request->all();
            $id = Auth::guard('user')->user()->id;
            $query = CustomerDesiredArea::where('id', $data['id'])->delete();
            if ($query) {
                $response =  CustomerDesiredArea::with('city_areas', 'city.prefecture')->where('customers_id', $id)->get();
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Frontend/MenuSettingController (remove Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Frontend/MenuSettingController (remove Function), Error: ", $e);
            //------------------------------------------------------

            $response = new \stdClass;
            $response->status = 'failed';
            $response->message = $e;

            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }

    public function store(Request $request)
    {
        $response = new \stdClass;
        try {
            $dataset           = json_decode($request->dataset, true);
            $data['city']      = City::with('prefecture')->find($dataset['cities_id']);
            $data['city_areas']  = CitiesAreas::find($dataset['cities_area_id']);
            $response->desired =  $data;
            $response->status = 'success';
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Frontend/MenuSettingController (store Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Frontend/MenuSettingController (store Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = 'failed';
            $response->message = $e;
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }

    public function customer(Request $request)
    {
        $response = new \stdClass;
        try {
            $dataset            = json_decode($request->dataset, true);

            $id                 = Auth::guard('user')->user()->id;
            $currentCustomer    = Customer::find($id);

            //$log['action_types_id'] = ActionType::CHANGE_MY_SETTING;;
            //$log['customers_id']    = $id;
            //$log['ip']              = $request->ip();
            //$log['access_time']     = Carbon::now();
            //$savelog = new CustomerLogActivity();
            //$savelog->fill($log)->save();

            //---------------------------------------------------
            //Save customer log activity when Change My Setting
            //---------------------------------------------------
            CustomerLogActivity::storeCustomerLog(ActionType::CHANGE_MY_SETTING, $id, $request->ip());
            //---------------------------------------------------

            foreach ($dataset['customer_desired_areas'] as $key => $value) {
                if ($value['id'] != -1) {
                    $desiredModel = CustomerDesiredArea::find($value['id']);
                    $desiredModel->default = $value['default'];
                    $desiredModel->save();
                } else {
                    $desiredModel = new CustomerDesiredArea();
                    $desiredModel->customers_id = $id;
                    $desiredModel->cities_id = $value['cities_id'];
                    $desiredModel->cities_area_id = $value['cities_area_id'];
                    $desiredModel->default = $value['default'];
                    $desiredModel->save();
                }
                $desiredIds[] = $desiredModel->id;
            }

            CustomerDesiredArea::whereNotIn('id', $desiredIds)->where('customers_id', $id)->delete();

            $response->lowest_price_filter = ListConsiderAmount::min('value');
            $response->highest_price_filter = ListConsiderAmount::max('value');
            $response->lowest_land_area_filter = ListLandArea::min('value');
            $response->highest_land_area_filter = ListLandArea::max('value');

            $currentDesired     = CustomerDesiredArea::where('id', $id)->where('default', '1')->first();

            if ($currentDesired != null) {
                $lat = "";
                $lng = "";
                if ($currentDesired->cities_id != null && $currentDesired->cities_area_id != null) {
                    $citiesAreas = CitiesAreas::find($currentDesired->cities_area_id);
                    $lat = $citiesAreas->lat;
                    $lng = $citiesAreas->lng;
                } else {
                    $city = City::find($currentDesired->cities_id);
                    $lat = $city->lat;
                    $lng = $city->lng;
                }
                $response->lat = $lat;
                $response->lng = $lng;
            } else {
                $response->lat = config('const.map_default.lat');
                $response->lng = config('const.map_default.lng');
            }

            $save = $currentCustomer->fill($dataset)->save();
            if ($save) {
                $response->customer =  Customer::where('id', $id)->first();
                $response->status = 'success';
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Frontend/MenuSettingController (customer Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Frontend/MenuSettingController (customer Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = 'failed';
            $response->message = $e;

            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }

    public function default(Request $request)
    {
        try {
            $id     = $request['id'];
            $userID = Auth::guard('user')->user()->id;
            $data   = CustomerDesiredArea::where([['id', $id], ['customers_id', $userID]])->first();
            if ($data->default == true) {
                $area['default'] = 0;
                $save = $data->fill($area)->save();
                if ($save) {
                    $response = CustomerDesiredArea::with('city_areas', 'city.prefecture')->where('customers_id', $userID)->get();
                    return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
                }
            } else {
                $check = CustomerDesiredArea::where([['default', 1], ['customers_id', $userID]])->exists();
                if ($check) {
                    $data   = CustomerDesiredArea::where([['default', 1], ['customers_id', $userID]])->first();
                    $area['default'] = 0;
                    $save = $data->fill($area)->save();
                    if ($save) {
                        $data = CustomerDesiredArea::where('id', $id)->first();
                        $area['default'] = 1;
                        $save = $data->fill($area)->save();
                        if ($save) {
                            $response = CustomerDesiredArea::with('city_areas', 'city.prefecture')->where('customers_id', $userID)->get();
                            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
                        }
                    }
                } else {
                    $area['default'] = 1;
                    $save = $data->fill($area)->save();
                    if ($save) {
                        $response = CustomerDesiredArea::with('city_areas', 'city.prefecture')->where('customers_id', $userID)->get();
                        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
                    }
                }
            }
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Frontend/MenuSettingController (default Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Frontend/MenuSettingController (default Function), Error: ", $e);
            //------------------------------------------------------
            //dd($e);
        }
    }
}
