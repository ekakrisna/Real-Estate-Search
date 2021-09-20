<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Frontend\Property;

use App\Http\Controllers\Controller;
use App\Models\ActionType;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// --------------------------------------------------------------------------
use App\Models\CustomerLogActivity as CustomerLog;
use App\Models\CustomerFavoriteProperty as Favorite;
use App\Models\CustomerLogActivity;
// --------------------------------------------------------------------------
use Illuminate\Support\Facades\Log;


// --------------------------------------------------------------------------
class PropertyFavoriteController extends Controller
{
    // ----------------------------------------------------------------------
    // Register/Remove favorite property
    // ----------------------------------------------------------------------
    public function toggle(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        try {
            // --------------------------------------------------------------
            $customer = Auth::guard('user')->user();
            if ($customer) {
                $customerID = $customer->id;
                $propertyID = $request->property;
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // Get the favorite entry
                // --------------------------------------------------------------
                $favorite = Favorite::where('customers_id', $customerID)
                    ->where('properties_id', $propertyID)->get();
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // If favorite entry is not found, create one
                // --------------------------------------------------------------
                if (empty($favorite) || $favorite->count() == 0) {
                    $favorite = new Favorite();
                    $favorite->fill([
                        'customers_id'  => $customerID,
                        'properties_id' => $propertyID,
                        'created_at'    => Carbon::now()
                    ])->save();
                    $response->result = true;
                }
                // --------------------------------------------------------------
                // Otherwise, delete it
                // --------------------------------------------------------------
                else {
                    foreach ($favorite as $key => $value) {
                        $value->delete();
                        $response->result = false;
                    }
                }
                // --------------------------------------------------------------

                // --------------------------------------------------------------
                // If customer is loggable, add the record log
                // --------------------------------------------------------------
                //$isLoggable = !$customer->not_leave_record;
                //if ($isLoggable && $response->result) {
                    //$customerLog = new CustomerLog();
                    //$dataset = factory(CustomerLog::class)->make([
                        //'ip' => $request->ip(),
                        //'action_types_id' => 5,
                        //'customers_id' => $customerID,
                        //'properties_id' => $propertyID,
                    //]);
                    //$customerLog->fill($dataset->toArray())->save();
                //}

                if ($response->result) {
                    //---------------------------------------------------
                    //Save customer log activity when View Property Detail
                    //---------------------------------------------------
                    CustomerLogActivity::storeCustomerLog(ActionType::PROPERTY_FAVORITES, $customerID, $request->ip(), $propertyID);
                    //---------------------------------------------------
                }

            // --------------------------------------------------------------
            }

            // --------------------------------------------------------------
        } catch (\Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Frontend/PropertyFavoriteController (toggle Function), Error: ".$e->getMessage());
            sendMessageOfErrorReport("Frontend/PropertyFavoriteController (toggle Function), Error: ", $e );
            //------------------------------------------------------

            $response->status = 'error';
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json($response);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
