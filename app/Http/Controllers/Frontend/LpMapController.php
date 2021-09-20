<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\CustomerDesiredArea;
use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;
use App\Models\Town;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LpMapController extends Controller
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
        /*
        if ($request->get('customer_desired_areas')) {
            $desired_area = $request->get('customer_desired_areas');
            $picked_desired_area = CustomerDesiredArea::findOrFail($desired_area);
            if ($picked_desired_area->towns_id == null) {
                $data['town'] = City::findOrFail($picked_desired_area->cities_id);
                $data['lat'] = $data['town']->lat;
                $data['lng'] = $data['town']->lng;
            } else {
                $data['town'] = Town::findOrFail($picked_desired_area->towns_id);
                $data['lat'] = $data['town']->lat;
                $data['lng'] = $data['town']->lng;
            }
        } else {
            $idCustomers = Auth::guard('user')->user()->id;
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
        */

        $data['title'] = __('label.map');
        $data['lowest_price_filter'] = ListConsiderAmount::min('value');
        $data['highest_price_filter'] = ListConsiderAmount::max('value');
        $data['lowest_land_area_filter'] = ListLandArea::min('value');
        $data['highest_land_area_filter'] = ListLandArea::max('value');

        return view('frontend.lp.map.index_new', $data);
    }
}
