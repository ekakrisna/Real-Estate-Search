<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Frontend\Property;

use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
// --------------------------------------------------------------------------
use App\Models\Prefecture;
use App\Models\City;
use App\Models\Town;
use App\Models\Customer;
use App\Models\Property;
use App\Models\CustomerDesiredArea as DesiredArea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class PropertyListController extends Controller
{
    // ----------------------------------------------------------------------
    public function index(Request $request)
    {
        // ------------------------------------------------------------------
        $data = new \stdClass;
        App::setLocale('jp'); // Set frontend locale
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $location = new \stdClass;
        $data->location = $location;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // When location string is not present,
        // Redirect to the customer's default desired area
        // ------------------------------------------------------------------
        if (empty($request->location)) {
            $mainDesiredArea = DesiredArea::getDefaultArea();
            if ($mainDesiredArea != null) {
                return redirect()->route('frontend.property.list.location', $mainDesiredArea->location);
            } else {
                return abort(404);
            }
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Parse the location
        // Location sample: '宮城県仙台市青葉区大町一丁目'
        // ------------------------------------------------------------------
        $location->query = $request->location;
        $parsedLocation = parseLocation($request->location);
        // ------------------------------------------------------------------
        $prefectureName = $parsedLocation['prefecture'];
        $cityName = $parsedLocation['city'];
        $townName = $parsedLocation['town'];
        // ------------------------------------------------------------------
        // Get the returned town
        // ------------------------------------------------------------------
        $prefecture = prefecture::where('name', $prefectureName)->first();
        $city = City::where('name', $cityName)->where('prefectures_id', $prefecture->id)->first();
        $town =  Town::with('city.prefecture')->where('name', $townName)->where('cities_id', $city->id)->first();
        $location->town = $town;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Whether the customer has this location as desired-area or not
        // ------------------------------------------------------------------
        $location->desired = Customer::hasDesiredLocation($request->location);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Current logged in customer
        // ------------------------------------------------------------------
        // $customerID = Auth::guard('user')->user()->id;
        // $customer = Customer::find( $customerID );
        // ------------------------------------------------------------------

        //$location->toggleDisabled = !$location->desired;
        $location->toggleDisabled = false;
        //if($location->desired){
        $customer = Auth::guard('user')->user();
        if (!empty($customer)) {
            $conditions = [
                'cities_id' => $city->id,
                'customers_id' => $customer->id
            ];
            // --------------------------------------------------------------
            $desiredAreas = DesiredArea::where($conditions)->get();
            foreach ($desiredAreas as $desiredArea) {
                if ($desiredArea->cities_area_id == null) {
                    $location->toggleDisabled = true;
                }
            }
        }
        //}

        // ------------------------------------------------------------------
        // return (array)$data;
        $data->title = __('label.property_information');
        return view('frontend.property.index', (array) $data);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Page filters
    // ----------------------------------------------------------------------
    public function filter(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        // ------------------------------------------------------------------
        $filter = (object) $request->filter ?? null;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Process location query
        // ------------------------------------------------------------------
        if (!empty($filter->location)) {
            // --------------------------------------------------------------
            $location = new \stdClass;
            $response->location = $location;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Parse the location
            // Location sample: '宮城県仙台市青葉区大町一丁目'
            // --------------------------------------------------------------
            $location->query = $filter->location;
            $parsedLocation = parseLocation($location->query);
            // --------------------------------------------------------------
            $prefectureName = $parsedLocation['prefecture'];
            $cityName = $parsedLocation['city'];
            $townName = $parsedLocation['town'];
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get the returned town
            // --------------------------------------------------------------
            $prefecture = prefecture::where('name', $prefectureName)->first();
            $city = City::where('name', $cityName)->where('prefectures_id', $prefecture->id)->first();
            $town =  Town::with('city.prefecture')->where('name', $townName)->where('cities_id', $city->id)->first();
            $location->town = $town;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Whether the customer has this location as desired-area or not
            // --------------------------------------------------------------
            $location->desired = Customer::hasDesiredLocation($location->town->full_address);
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Process pagination
            // --------------------------------------------------------------
            $perpage = 10;
            $page = $filter->page ?? 1;
            // --------------------------------------------------------------
            $relations = collect();
            $customer = Auth::guard('user')->user();
            if ($customer) {
                $customer_id = $customer->id;

                $query = Property::where('location', $filter->location)->OtherThanBackUp()
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
                    });
                // --------------------------------------------------------------
            } else {
                $query = Property::where('location', $filter->location)->where('property_statuses_id', 2)->OtherThanBackUp();
            }
            // --------------------------------------------------------------
            // Price
            // --------------------------------------------------------------
            $minPrice = !empty($filter->minimum_price) && $filter->minimum_price != "null" ? $filter->minimum_price : '';
            $maxPrice = !empty($filter->maximum_price) && $filter->maximum_price != "null" ? $filter->maximum_price : '';
            $query->PriceRange($minPrice, $maxPrice);
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Land Area
            // --------------------------------------------------------------
            $minLandArea = !empty($filter->minimum_land_area) && $filter->minimum_land_area != "null" ? $filter->minimum_land_area : '';
            $maxLandArea = !empty($filter->maximum_land_area) && $filter->maximum_land_area != "null" ? $filter->maximum_land_area : '';
            $query->LandAreaRange($minLandArea, $maxLandArea);
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Paginate the result
            // --------------------------------------------------------------
            $query->with($relations->unique()->all());
            $paginator = $query->paginate($perpage, ['*'], 'page', $page);
            $response->result = $paginator;
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Toggle location as desired-area
    // ----------------------------------------------------------------------
    public function toggleDesiredLocation(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'error';
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        try {
            // --------------------------------------------------------------
            // If location parameter is not present, return error
            // --------------------------------------------------------------
            if (empty($request->location)) return response()->json($response);
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Add or remove location from the Customer's desired area
            // --------------------------------------------------------------
            $response->result = Customer::toggleDesiredLocation($request->location);
            $response->status = 'success';
            // --------------------------------------------------------------

            // --------------------------------------------------------------
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Frontend/PropertyListController (toggle Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Frontend/PropertyListController (toggle Function), Error: ", $e);
            //------------------------------------------------------

            return response()->json($response);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json($response);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
