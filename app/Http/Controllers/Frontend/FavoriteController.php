<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Collection;
use App\Helpers\DatatablesHelper;
use App\Http\Controllers\Controller;
use App\Models\ActionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Customer;
use App\Models\CustomerContactUs;
use App\Models\CustomerDesiredArea;
use App\Models\CustomerFavoriteProperty;
use App\Models\CustomerLogActivity;
use App\Models\Property;
use App\Models\Town;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $data = new \stdClass;
        $idCustomers = FacadesAuth::guard('user')->user()->id;
        $data->idCustomers  = $idCustomers;
        //$currentCustomer    = Customer::find($idCustomers);
        //$notleave           = $currentCustomer->not_leave_record;
        //if(!$notleave){
        //$log['action_types_id'] = 4;
        //$log['customers_id']    = $idCustomers;
        //$log['ip']              = $request->ip();
        //$log['access_time']     = Carbon::now();
        //$savelog = new CustomerLogActivity();
        //$savelog->fill($log)->save();
        //}

        //---------------------------------------------------
        //Save customer log activity when visiting favorite page
        //---------------------------------------------------
        CustomerLogActivity::storeCustomerLog(ActionType::PROPERTY_BROWSING, $idCustomers, $request->ip());
        //---------------------------------------------------

        // $data->propertyList =  CustomerFavoriteProperty::with('property.property_photos.file')->where('customers_id', 'LIKE', '%'.$idCustomers.'%')->get();
        // dd($data);
        $data->title = __('label.favorite');
        return view('frontend.favorite.index', (array) $data);
    }

    public function removeFavorite(Request $request)
    {
        $data                    = $request->all();
        $idCustomers = FacadesAuth::guard('user')->user()->id;
        // If not_leave_record of customers is false, set action_types_id of customer_log_activities to 5 and register the history.
        //$currentCustomer    = Customer::find($idCustomers);
        //$notleave           = $currentCustomer->not_leave_record;
        //if (!$notleave) {
        //$log['action_types_id'] = 5;
        //$log['customers_id']    = $idCustomers;
        //$log['properties_id']   = $data['properties_id'];
        //$log['ip']              = $request->ip();
        //$log['access_time']     = Carbon::now();
        //$savelog = new CustomerLogActivity();
        //$savelog->fill($log)->save();
        //}

        //---------------------------------------------------
        //Save customer log activity when visiting favorite page
        //---------------------------------------------------
        CustomerLogActivity::storeCustomerLog(ActionType::PROPERTY_FAVORITES, $idCustomers, $request->ip(), $data['properties_id']);
        //---------------------------------------------------

        $query = CustomerFavoriteProperty::where('id', $data['id'])->delete();
        if ($query) {
            $value['propertyList'] =  CustomerFavoriteProperty::with('property.property_photos.file')->where('customers_id', 'LIKE', '%' . $idCustomers . '%')->get();
            return $value;
        }
    }

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
        // Pagination parameters
        // ------------------------------------------------------------------
        $perpage = 10;
        $page = $filter->page ?? 1;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Base query
        // ------------------------------------------------------------------
        $customerID = Auth::guard('user')->user()->id;
        $query = CustomerFavoriteProperty::where('customers_id', $customerID);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Relationhip
        // ------------------------------------------------------------------
        $relations = collect(['property.photos.file']);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Paginate the result
        // ------------------------------------------------------------------
        $query->with($relations->unique()->all());
        $paginator = $query->paginate($perpage, ['*'], 'page', $page);
        $response->result = $paginator;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
