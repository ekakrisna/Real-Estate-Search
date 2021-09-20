<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\CommonMeasure;
use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;
use App\Models\LpProperty as Property;
use App\Models\Town;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class LpMapController extends Controller
{
    // ------------------------------------------------------------------
    // C40 LP Map - Get Town with that has property in it's area
    // ------------------------------------------------------------------
    public function getPropertyList(Request $reqest)
    {        
        // ------------------------------------------------------------------
        // variable for return
        // ------------------------------------------------------------------
        $resultJson = null;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // get Town list from display area of google map
        // ------------------------------------------------------------------
        $townList = Town::with('city.prefecture')
            ->where('lat', '>=', $reqest->get('south'))
            ->where('lat', '<=', $reqest->get('north'))
            ->where('lng', '>=', $reqest->get('west'))
            ->where('lng', '<=', $reqest->get('east'))
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
        $filterMinPrice = fromMan($reqest->get('min_price'));
        $filterMaxPrice = fromMan($reqest->get('max_price'));

        $minPrice = $filterMinPrice < $lowest_price ? '' : $reqest->get('min_price');
        $maxPrice = $filterMaxPrice > $highest_price ? '' : $reqest->get('max_price');

        // ------------------------------------------------------------------
        //  Get value of Land Area for search condition
        // ------------------------------------------------------------------
        $filterMinLandArea = fromTsubo($reqest->get('min_landArea'));
        $filterMaxLandArea = fromTsubo($reqest->get('max_landArea'));

        $minLandArea =  $filterMinLandArea < $lowest_land_area ? '' : toTsubo($filterMinLandArea);
        $maxLandArea =  $filterMaxLandArea > $highest_land_area ? '' : toTsubo($filterMaxLandArea);
        
        // GET PROPERTY LIST
        $propertyList = Property::whereIn('location', $fullAddressArray)
            ->PriceRange($minPrice, $maxPrice)
            ->LandAreaRange($minLandArea, $maxLandArea)->get();

        // ------------------------------------------------------------------
        //  Get list of more infomation of location
        // ------------------------------------------------------------------
        $newLocationNameArray = [];

        //Get List of location have new property and favorite property. 
        foreach ($propertyList as $property) {
            if ($property->created_at > Carbon::now()->subDays(90)->toDateTimeString()) {
                $newLocationNameArray[] =  $property->location;
            }
        }

        // ------------------------------------------------------------------
        // Get Filtered Property
        // ------------------------------------------------------------------

        $totalCommonMeasure->startMeasure();
        foreach ($townList as $town) {

            $fullAddress =  $town->city->prefecture->name . $town->city->name . $town->name;
            $serachProperty = $propertyList->where('location', $fullAddress);
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
            ];
            // ------------------------------------------------------------------
        }

        $totalCommonMeasure->endMeasure();

        // ------------------------------------------------------------------
        // send array to frontend
        // ------------------------------------------------------------------
        return json_encode($resultJson);
        // ------------------------------------------------------------------
    }
    // ------------------------------------------------------------------

    public function getProperty(Request $request)
    {
        // ------------------------------------------------------------------
        if (!empty($request->all())) {
            // --------------------------------------------------------------
            // Price
            // --------------------------------------------------------------
            $minPrice = !empty($request->minimum_price) && $request->minimum_price != "null" ? $request->minimum_price : '';
            $maxPrice = !empty($request->maximum_price) && $request->maximum_price != "null" ? $request->maximum_price : '';
            // --------------------------------------------------------------
            // Land Area
            // --------------------------------------------------------------
            $minLandArea = !empty($request->minimum_land_area) && $request->minimum_land_area != "null" ? $request->minimum_land_area : '';
            $maxLandArea = !empty($request->maximum_land_area) && $request->maximum_land_area != "null" ? $request->maximum_land_area : '';

            $query = Property::where('location', $request->location)
                ->PriceRange($minPrice, $maxPrice)
                ->LandAreaRange($minLandArea, $maxLandArea)
                ->get(['id', 'location', 'minimum_price', 'minimum_land_area', 'building_area', 'house_layout', 'building_age', 'connecting_road', 'contracted_years'])
                ->makeHidden(['url', 'ja', 'label']);
                // --------------------------------------------------------------            

            $result = [];
            foreach ($query as $property) {
                $result[] = [
                    'id' => $property->id,
                    'location' => $property->location,
                    'price' => $property->minimum_price,
                    'land_area' => $property->minimum_land_area,
                    'building_area' => $property->building_area,
                    'house_layout' => $property->house_layout,
                    'building_age' => $property->building_age,
                    'connecting_road' => $property->connecting_road,
                    'contracted_years' => $property->contracted_years,
                ];
            }
            
            return Response::Json($result);
        }
    }
}
