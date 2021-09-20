<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

use App\Models\CustomerSearchHistory;

class CustomerSearcHistoryController extends Controller
{
    public function store(Request $request){
        // ------------------------------------------------------------------
        //  check if customer already has customer history data
        // ------------------------------------------------------------------
        //$customer_search_history = CustomerSearchHistory::where('customers_id',$request->get('customers_id'))->first();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        //  if true, update the data
        // ------------------------------------------------------------------
        //if($customer_search_history) 
        //{
            //$customer_search_history->location = $request->get('location');
            //$customer_search_history->minimum_price = $request->get('minimum_price') ? fromMan($request->get('minimum_price')) : null;
            //$customer_search_history->maximum_price = $request->get('maximum_price') ? fromMan($request->get('maximum_price')) : null;
            //$customer_search_history->minimum_land_area = $request->get('minimum_land_area') ? fromTsubo($request->get('minimum_land_area')) : null;
            //$customer_search_history->maximum_land_area = $request->get('maximum_land_area') ? fromTsubo($request->get('maximum_land_area')) : null;
            //$customer_search_history->save();
        //}
        // ------------------------------------------------------------------ 
        //else
        // ------------------------------------------------------------------
        // if false, create a new one
        // ------------------------------------------------------------------ 
        //{
            //$customer_search_history = CustomerSearchHistory::create([
                //'customers_id'      => $request->get('customers_id'),
                //'location'          => $request->get('location'),
                //'minimum_price'     => $request->get('minimum_price') ? fromMan($request->get('minimum_price')) : null,
                //'maximum_price'     => $request->get('maximum_price') ? fromMan($request->get('maximum_price')) : null,
                //'minimum_land_area' => $request->get('minimum_land_area') ? fromTsubo($request->get('minimum_land_area')) : null,
                //'maximum_land_area' => $request->get('maximum_land_area') ? fromTsubo($request->get('maximum_land_area')) : null,
            //]);
        //}
        // ------------------------------------------------------------------

        $customers_id = $request->get('customers_id');
        $location = $request->get('location');
        $filterMinPrice = $request->get('minimum_price') ? fromMan($request->get('minimum_price')) : null;
        $filterMaxPrice = $request->get('maximum_price') ? fromMan($request->get('maximum_price')) : null;
        $filterMinLandArea = $request->get('minimum_land_area') ? fromTsubo($request->get('minimum_land_area')) : null;
        $filterMaxLandArea = $request->get('maximum_land_area') ? fromTsubo($request->get('maximum_land_area')) : null;
        
        $customer_search_history = CustomerSearchHistory::StoreCustomerSearchHistory($customers_id, $location, $filterMinPrice, $filterMaxPrice, $filterMinLandArea, $filterMaxLandArea);
        
        //-----------------------------------------------------------------------------------

        // ------------------------------------------------------------------
        // return response
        // ------------------------------------------------------------------
        return json_encode($customer_search_history);
        // ------------------------------------------------------------------
    }
}
