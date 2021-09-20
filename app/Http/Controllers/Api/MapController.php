<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use App\Models\Property;
use App\Models\Town;
use App\Models\Customer;
use App\Models\CustomerFavoriteProperty;
use App\Models\CustomerSearchHistory;
use App\Models\CustomerDesiredArea;
use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;
use App\Lib\CommonMeasure;
use App\Models\ActionType;
use App\Models\CustomerLogActivity;
use App\Models\BeforeLoginCustomerLoginLogActivities;
use SebastianBergmann\Environment\Console;

class MapController extends Controller
{
    // ------------------------------------------------------------------
    // C3 Map - Get Town with that has property in it's area
    // ------------------------------------------------------------------
    public function getPropertyList(Request $request)
    {

        // ------------------------------------------------------------------
        // variable for return
        // ------------------------------------------------------------------
        $resultJson = null;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get info of logged in customer
        // ------------------------------------------------------------------
        if ($request->cust_id) {
            // get customer information.
            $customer_id = $request->cust_id;
            $customer = Customer::find($customer_id);
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // get Town list from display area of google map
            // ------------------------------------------------------------------
            $townList = Town::with('city.prefecture', 'city_areas')
                ->where('lat', '>=', $request->south)
                ->where('lat', '<=', $request->north)
                ->where('lng', '>=', $request->west)
                ->where('lng', '<=', $request->east)
                ->get();

            $lowest_price = ListConsiderAmount::min('value');
            $highest_price = ListConsiderAmount::max('value');
            $lowest_land_area = ListLandArea::min('value');
            $highest_land_area = ListLandArea::max('value');

            $totalCommonMeasure = new CommonMeasure();
            $propertyCommonMeasure = new CommonMeasure();
            $newCommonMeasure = new CommonMeasure();
            $favCommonMeasure = new CommonMeasure();
            $desiredCommonMeasure = new CommonMeasure();
            $notPropertyCount = 0;

            // ------------------------------------------------------------------

            $fullAddressArray = [];
            foreach ($townList as $town) {
                $fullAddressArray[] =  $town->city->prefecture->name . $town->city->name . $town->name;
            }
            // dd($fullAddressArray);
            // ------------------------------------------------------------------
            // Get value of Price for search condition
            // -----------------------------------------------------------------
            $filterMinPrice = fromMan($request->min_price);
            $filterMaxPrice = fromMan($request->max_price);

            $minPrice = $filterMinPrice < $lowest_price ? '' : $request->min_price;
            $maxPrice = $filterMaxPrice > $highest_price ? '' : $request->max_price;

            // ------------------------------------------------------------------
            //  Get value of Land Area for search condition
            // ------------------------------------------------------------------
            $filterMinLandArea = fromTsubo($request->min_landArea);
            $filterMaxLandArea = fromTsubo($request->max_landArea);

            $minLandArea =  $filterMinLandArea < $lowest_land_area ? '' : toTsubo($filterMinLandArea);
            $maxLandArea =  $filterMaxLandArea > $highest_land_area ? '' : toTsubo($filterMaxLandArea);

            // GET PROPERTY LIST
            $propertyList = Property::whereIn('location', $fullAddressArray)->OtherThanBackUp()
                ->where(function ($query) use ($customer_id) {
                    $query->where('property_statuses_id', 2)
                        // --------------------------------------------------------------
                        // if property status is 3
                        // -> check if it has relation to property_publishing_setting
                        //    and the customers_id of the relation is matched with
                        //    customer id that currently logged in
                        // --------------------------------------------------------------
                        ->orWhere(function ($query) use ($customer_id) {
                            $query->where('property_statuses_id', 3)
                                ->whereHas('property_publishing_setting', function (Builder $sale) use ($customer_id) {
                                    $sale->where('customers_id', $customer_id);
                                });
                        });
                    // --------------------------------------------------------------
                })
                ->PriceRange($minPrice, $maxPrice)
                ->LandAreaRange($minLandArea, $maxLandArea)->get();

            // ------------------------------------------------------------------
            //  Get list of more infomation of location
            // ------------------------------------------------------------------
            $newLocationNameArray = [];
            $favoritLocationNameArray = [];

            $customerOfFavoritePropertyIdArray = CustomerFavoriteProperty::where('customers_id', $customer_id)->pluck('properties_id')->toArray();

            //Get List of location have new property and favorite property.
            foreach ($propertyList as $property) {
                if ($property->created_at > Carbon::now()->subDays(30)->toDateTimeString()) {
                    $newLocationNameArray[] =  $property->location;
                }

                if (in_array($property->id, $customerOfFavoritePropertyIdArray)) {
                    $favoritLocationNameArray[] = $property->location;
                }
            }

            $customerDesiredAreasCityAreaIdArray = CustomerDesiredArea::where('customers_id', $customer_id)->pluck('cities_area_id')->toArray();
            $customerDesiredAreasCityIdArray = CustomerDesiredArea::where('customers_id', $customer_id)->whereNull('cities_area_id')->pluck('cities_id')->toArray();

            // ------------------------------------------------------------------
            // Get Filtered Property
            // ------------------------------------------------------------------

            $alreadyHistoryPropertyids = CustomerLogActivity::where('action_types_id', ActionType::PROPERTY_BROWSING)->where('customers_id', $customer_id)->pluck('properties_id')->toArray();
            $propertyHistoryModel = Property::whereIn('id', $alreadyHistoryPropertyids)->get();

            $totalCommonMeasure->startMeasure();
            foreach ($townList as $town) {

                $fullAddress =  $town->city->prefecture->name . $town->city->name . $town->name;
                $serachProperty = $propertyList->where('location', $fullAddress);
                $idProperty = $serachProperty->pluck('id')->toArray();
                $browsedProperty = $propertyHistoryModel->where('location', $fullAddress);

                if ($browsedProperty->count() != 0) {
                    $idBrowsed = $browsedProperty->pluck('properties_id')->toArray();

                    if ($browsedProperty->count() == $serachProperty->count() || count(array_intersect($idProperty, $idBrowsed)) == $serachProperty->count()) {
                        $browsed = true;
                    } else {
                        $browsed = false;
                    }
                } else {
                    $browsed = false;
                }

                if ($serachProperty->count() == 0) {
                    continue;
                }

                $isNewProperty = in_array($fullAddress, $newLocationNameArray);
                $isFavoriteProperty = in_array($fullAddress, $favoritLocationNameArray);
                $isFavoriteArea = in_array($town->city->id, $customerDesiredAreasCityIdArray);
                if (!$isFavoriteArea) {
                    $isFavoriteArea = in_array($town->city_areas->id, $customerDesiredAreasCityAreaIdArray);
                }

                // ------------------------------------------------------------------
                //  push item to array
                // ------------------------------------------------------------------
                $resultJson[] = [
                    'label' => $serachProperty->count(),
                    'section_id' => 1,
                    'name' => $fullAddress,
                    'lat' => $town->lat,
                    'lng' => $town->lng,
                    'new' => $isNewProperty,
                    'fav' => $isFavoriteProperty,
                    'area' => $isFavoriteArea,
                    'town'  => $town,
                    'properties' => $serachProperty,
                    'browsed' => $browsed,
                ];
                // ------------------------------------------------------------------
            }

            $totalCommonMeasure->endMeasure();

            /*
            Log::debug("processTotaltime,". $totalCommonMeasure->getTotalResult().
            ",PinTotalCount,".count($resultJson).',NotPinTotalCount,'.$notPropertyCount.',GetPropertyTotalTime,'.$propertyCommonMeasure->getTotalResult().
            ',GetNewTotalTime,'.$newCommonMeasure->getTotalResult().',GetFavTotalTime,'.$favCommonMeasure->getTotalResult().
            ',GetDesiredTotalTime,'.$desiredCommonMeasure->getTotalResult());
            */

            // store custoemr serach data
            if ($request->is_serach_push) {

                //-----------------------------------------------------------------------------------
                // Not store history record if not_leave_record is flagged (that mean restriction to send my searching history: privacy setting)
                //-----------------------------------------------------------------------------------
                $location = $request->serachText;
                $filterMinPrice = fromMan($request->min_price) > $lowest_price ? fromMan($request->min_price) : NULL;
                $filterMaxPrice = fromMan($request->max_price) < $highest_price ? fromMan($request->max_price) : NULL;
                $filterMinLandArea = fromTsubo($request->min_landArea) > $lowest_land_area ?  fromTsubo($request->min_landArea) : NULL;
                $filterMaxLandArea = fromTsubo($request->max_landArea) < $highest_land_area ?  fromTsubo($request->max_landArea) : NULL;

                $newRequest = new Request();
                $newRequest->customers_id = $customer_id;
                $newRequest->location = $location;
                $newRequest->minimum_price = $filterMinPrice;
                $newRequest->maximum_price = $filterMaxPrice;
                $newRequest->minimum_land_area = $filterMinLandArea;
                $newRequest->maximum_land_area = $filterMaxLandArea;

                //$customer_search_history = CustomerSearchHistory::StoreCustomerSearchHistory($newRequest);

                //$aaaa = CustomerSearchHistory::create([
                //'customers_id' =>  $customer_id,
                //'location'    => $location,
                //'minimum_price' => $filterMinPrice,
                //'maximum_price' => $filterMaxPrice,
                //'minimum_land_area' =>  $filterMinLandArea,
                //'maximum_land_area' =>  $filterMaxLandArea,
                //]);
                //-----------------------------------------------------------------------------------
            }
        } else {
            // ------------------------------------------------------------------
            // get Town list from display area of google map
            // ------------------------------------------------------------------
            $townList = Town::with('city.prefecture')
                ->where('lat', '>=', $request->south)
                ->where('lat', '<=', $request->north)
                ->where('lng', '>=', $request->west)
                ->where('lng', '<=', $request->east)
                ->get();

            // get lowest and highet value of price and land area
            $lowest_price = ListConsiderAmount::min('value');
            $highest_price = ListConsiderAmount::max('value');
            $lowest_land_area = ListLandArea::min('value');
            $highest_land_area = ListLandArea::max('value');

            $totalCommonMeasure = new CommonMeasure();

            // ------------------------------------------------------------------
            $fullAddressArray = [];
            foreach ($townList as $town) {
                $fullAddressArray[] =  $town->city->prefecture->name . $town->city->name . $town->name;
            }

            // ------------------------------------------------------------------
            // Get value of Price for search condition
            // -----------------------------------------------------------------
            $filterMinPrice = fromMan($request->min_price);
            $filterMaxPrice = fromMan($request->max_price);

            $minPrice = $filterMinPrice < $lowest_price ? '' : $request->min_price;
            $maxPrice = $filterMaxPrice > $highest_price ? '' : $request->max_price;

            // ------------------------------------------------------------------
            //  Get value of Land Area for search condition
            // ------------------------------------------------------------------
            $filterMinLandArea = fromTsubo($request->min_landArea);
            $filterMaxLandArea = fromTsubo($request->max_landArea);

            $minLandArea =  $filterMinLandArea < $lowest_land_area ? '' : toTsubo($filterMinLandArea);
            $maxLandArea =  $filterMaxLandArea > $highest_land_area ? '' : toTsubo($filterMaxLandArea);

            // GET PROPERTY LIST
            $propertyList = Property::whereIn('location', $fullAddressArray)->OtherThanBackUp()
                ->where('property_statuses_id', 2)
                ->PriceRange($minPrice, $maxPrice)
                ->LandAreaRange($minLandArea, $maxLandArea)->get();

            // ------------------------------------------------------------------
            //  Get list of more infomation of location
            // ------------------------------------------------------------------
            $newLocationNameArray = [];

            //Get List of location have new property and favorite property.
            foreach ($propertyList as $property) {
                if ($property->created_at > Carbon::now()->subDays(30)->toDateTimeString()) {
                    $newLocationNameArray[] =  $property->location;
                }
            }

            // ------------------------------------------------------------------
            // Get Filtered Property
            // ------------------------------------------------------------------
            $alreadyHistoryPropertyids = BeforeLoginCustomerLoginLogActivities::where('uuid', $request->uuid)->pluck('properties_id')->toArray();
            $propertyHistoryModel = Property::whereIn('id', $alreadyHistoryPropertyids)->get();

            $totalCommonMeasure->startMeasure();
            foreach ($townList as $town) {

                $fullAddress =  $town->city->prefecture->name . $town->city->name . $town->name;
                $serachProperty = $propertyList->where('location', $fullAddress);
                $idProperty = $serachProperty->pluck('id')->toArray();
                $browsedProperty = $propertyHistoryModel->where('location', $fullAddress);

                if ($browsedProperty->count() != 0) {
                    $idBrowsed = $browsedProperty->pluck('properties_id')->toArray();

                    if ($browsedProperty->count() == $serachProperty->count() || count(array_intersect($idProperty, $idBrowsed)) == $serachProperty->count()) {
                        $browsed = true;
                    } else {
                        $browsed = false;
                    }
                } else {
                    $browsed = false;
                }

                if ($serachProperty->count() == 0) {
                    continue;
                }

                $isNewProperty = in_array($fullAddress, $newLocationNameArray);

                // ------------------------------------------------------------------
                //  push item to array
                // ------------------------------------------------------------------
                $resultJson[] = [
                    'label' => $serachProperty->count(),
                    'section_id' => 1,
                    'name' => $fullAddress,
                    'lat' => $town->lat,
                    'lng' => $town->lng,
                    'new' => $isNewProperty,
                    'town'  => $town,
                    'properties' => $serachProperty,
                    'browsed' => $browsed,
                ];
                // ------------------------------------------------------------------
            }

            $totalCommonMeasure->endMeasure();
        }

        // ------------------------------------------------------------------
        // send array to frontend
        // ------------------------------------------------------------------
        return json_encode($resultJson);
        // ------------------------------------------------------------------
    }
    // ------------------------------------------------------------------

    public function getUserSetting(Request $request)
    {
        // variable for return
        $resultJson;

        /**
         * TODO : 13 JAN 2021 written by Endo
         * if it have implemented function of Customer login,need get Customer info from DataBase.
         */

        // if it have implemented function of Customer login, It is used.
        $user = Customer::findOrFail($request->cust_id);

        $resultJson = [
            'priceMin' => $user->minimum_price,
            'priceMax' => $user->maximum_price,
            'landAreaMin' => $user->minimum_land_area,
            'landAreaMax' => $user->maximum_land_area,
        ];

        // only use test
        // $resultJson =[
        //     'priceMin' => 10000000,
        //     'priceMax' =>50000000,
        //     'landAreaMin' => 5,
        //     'landAreaMax' => 40,
        // ];

        return json_encode($resultJson);
    }
}
