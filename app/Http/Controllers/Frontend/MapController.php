<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\CustomerDesiredArea;
use App\Models\City;
use App\Models\Customer;
use App\Models\Town;

use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MapController extends Controller
{
    public function map(Request $request)
    {
        // ------------------------------------------------------------------
        // initial data
        // for condition if customer didn't have any desired area
        // ------------------------------------------------------------------
        $data['town'] = null;
        $data['lat'] = 0;
        $data['lng'] = 0;

        if (Auth::guard('user')->user()) {
            // ------------------------------------------------------------------
            // GET CUSTOMER BY LOGIN
            // ------------------------------------------------------------------
            $idCustomers = Auth::guard('user')->user()->id;

            // ------------------------------------------------------------------
            // GET CUSTOMER BY LOGIN
            // ------------------------------------------------------------------
            $customer = Customer::findOrFail($idCustomers);
            $data['customer'] = $customer;

            $currentCustomer    = CustomerDesiredArea::where('customers_id', $idCustomers)->where('default', '1')->first();
            if ($currentCustomer != null) {
                $lat = "";
                $lng = "";
                if ($currentCustomer->cities_id != null && $currentCustomer->towns_id != null) {
                    $town = Town::find($currentCustomer->towns_id);
                    $lat = $town->lat;
                    $lng = $town->lng;
                } else {
                    $city = City::find($currentCustomer->cities_id);
                    $lat = $city->lat;
                    $lng = $city->lng;
                }
                $data['lat'] = $lat;
                $data['lng'] = $lng;
            }
        }

        $data['title'] = __('label.map');
        $data['lowest_price_filter'] = ListConsiderAmount::min('value');
        $data['highest_price_filter'] = ListConsiderAmount::max('value');
        $data['lowest_land_area_filter'] = ListLandArea::min('value');
        $data['highest_land_area_filter'] = ListLandArea::max('value');

        return view('frontend.map.index_new', $data);
    }

    public function store(Request $request)
    {
        $idCustomers        = Auth::guard('user')->user()->id;
        try {
            // ------------------------------------------------------------------
            // SAVE DESIRED AREA
            // ------------------------------------------------------------------
            if ($request->customer_desired_areas) {
                $currentCustomer    = CustomerDesiredArea::where('customers_id', $idCustomers)->get();
                foreach ($currentCustomer as $key => $value) {
                    $value->default = 0;
                    $value->save();
                }
                $idDesiredArea      = $request->customer_desired_areas;
                $currentDesired     = CustomerDesiredArea::where('id', $idDesiredArea)->first();

                if ($currentDesired->id != null) {
                    $currentDesired->default = 1;
                    $currentDesired->created_at = Carbon::now();
                    $currentDesired->save();

                    $lat = "";
                    $lng = "";

                    if ($currentDesired->cities_id != null && $currentDesired->cities_area_id != null) {
                        foreach ($currentDesired->city_areas->towns->sortBy('name') as $town) {
                            $town_id = $town->id;
                            break;
                        }

                        $town = Town::find($town_id);
                        $lat = $town->lat;
                        $lng = $town->lng;
                    } else {
                        $city = City::find($currentDesired->cities_id);
                        $lat = $city->lat;
                        $lng = $city->lng;
                    }
                    $data['lat'] = $lat;
                    $data['lng'] = $lng;
                }
                $data['customer_desired_areas'] = $currentDesired->id;
            } else {
                $data['lat'] = config('const.map_default.lat');
                $data['lng'] = config('const.map_default.lng');
                $data['customer_desired_areas'] = null;
            }
            // ------------------------------------------------------------------
            // Save Customer
            // ------------------------------------------------------------------
            $customer = Customer::findOrFail($idCustomers);
            $customer->minimum_price = $request->minimum_price;
            $customer->maximum_price = $request->maximum_price;
            $customer->minimum_price_land_area = $request->minimum_price_land_area;
            $customer->maximum_price_land_area = $request->maximum_price_land_area;
            $customer->minimum_land_area = $request->minimum_land_area;
            $customer->maximum_land_area = $request->maximum_land_area;
            $customer->save();

            $data['lowest_price_filter'] = ListConsiderAmount::min('value');
            $data['highest_price_filter'] = ListConsiderAmount::max('value');
            $data['lowest_land_area_filter'] = ListLandArea::min('value');
            $data['highest_land_area_filter'] = ListLandArea::max('value');
            $data['customer'] = $customer;
            $data['status'] = 'success';
            // ------------------------------------------------------------------
            // Save Customer
            // ------------------------------------------------------------------
            return response()->json($data, 200);
        } catch (\Throwable $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Frontend/MapController (store Function), Error: ", $e);
            //------------------------------------------------------

            throw $e;
        }
    }
}
