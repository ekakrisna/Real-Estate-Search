<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Collection;
use App\Helpers\DatatablesHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use App\Models\Customer;
use App\Models\CustomerLogActivity;
use App\Models\CustomerDesiredArea;
use App\Models\CustomerFavoriteProperty;
use App\Models\Property;
use App\Models\PropertyPhoto;
use App\Models\File;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class HistoryController extends Controller
{
    // public function index(){
    //     $id = Auth::guard('user')->user()->id;
        
    //     $query = CustomerLogActivity::select('file.name','file.extension','properties.location','properties.building_conditions','properties.created_at','properties.updated_at','properties.minimum_price','properties.maximum_price','properties.minimum_land_area','properties.maximum_land_area','customer_log_activities.access_time')
    //             ->join('customers','customers.id','=','customer_log_activities.customers_id')
    //             ->join('properties', function($join){
    //                 $join->on('customer_log_activities.properties_id','=','properties.id')
    //                     ->join('property_photos','properties.id','=','property_photos.properties_id')
    //                     ->join('file','property_photos.file_id','=','file.id');
    //             })
    //             ->where('customer_log_activities.customers_id', $id)->get();
       
    //     return view('frontend.history.index', [ 'query' => $query ]);
    // }
    
    public function index(Request $request){
        $data = new \stdClass;
        $idCustomers = FacadesAuth::guard('user')->user()->id;  
        $data->idCustomers  = $idCustomers;
        $currentCustomer    = Customer::find($idCustomers);
        $property           = Property::all();
        $notleave           = $currentCustomer->not_leave_record;
         
        // ------------------------------------------------------------------
        // get data from logged in cust id where action type is 4
        // ------------------------------------------------------------------
        $data->historyList = CustomerLogActivity::with('property.property_photos.file')
                                                    ->where('customers_id', $idCustomers)
                                                    ->where('action_types_id', 4)
                                                    ->get();
        // ------------------------------------------------------------------
        $data['title'] = __('label.news');
        return view('frontend.history.index', (array) $data);
    }

    public function removeFavorite(Request $request){
        $data                       = $request->all();      
        $idCustomers = Auth::guard('user')->user()->id;
        // If not_leave_record of customers is false, set action_types_id of customer_log_activities to 5 and register the history.
        //$currentCustomer    = Customer::find($idCustomers);
        //$notleave           = $currentCustomer->not_leave_record;
        //if (!$notleave) {
            //$log['action_types_id'] = 5;
            //$log['customers_id']    = $idCustomers;
            //$log['properties_id']   = $id;
            //$log['ip']              = $request->ip();
            //$log['access_time']     = Carbon::now();
            //$savelog = new CustomerLogActivity();
            //$savelog->fill($log)->save();
        //}          
        
        //---------------------------------------------------
        //Save customer log activity when removeFavorite
        //---------------------------------------------------
        CustomerLogActivity::storeCustomerLog(ActionType::PROPERTY_FAVORITES, $idCustomers, $request->ip(), $id);
        //---------------------------------------------------

        $query = CustomerFavoriteProperty::where('id',$data['id'])->delete();  
        if($query){
            $value['historyList'] =  CustomerLogActivity::with('property.property_photos.file')->where('customers_id', 'LIKE', '%'.$idCustomers.'%')->join('properties','properties.id','=','properties_id')->get();                                 
            return $value;
        }                    
    }
}
