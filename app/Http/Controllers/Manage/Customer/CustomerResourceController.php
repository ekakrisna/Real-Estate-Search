<?php

namespace App\Http\Controllers\Manage\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Response;
use Carbon\Carbon;
use App\Helpers\DatatablesHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;

use App\Models\Customer;
use App\Models\CustomerLogActivity;
use App\Models\CustomerDesiredArea;
use App\Models\Prefecture;
use App\Models\City;
use App\Models\Town;
use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;
use App\Models\ActionType;

use App\Models\CustomerFavoriteProperty;
use App\Models\CustomerSearchHistory;
use App\Models\CustomerContactUs;
use App\Models\GroupLine;
use Illuminate\Support\Facades\Hash;
use App\Traits\LogActivityTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\CompanyUserLogActivity;

class CustomerResourceController extends Controller
{
    use LogActivityTrait;

    public function __construct()
    {
    }

    protected function validator(array $data, $type)
    {
        return Validator::make($data, [
            'name'          => 'required|string|max:45',
            'email'         => 'required|email|max:191',
            'password'      => $type == 'create' ? 'required|string|min:8|max:191' : 'string|min:8|max:191'
        ]);
    }

    //FUNCTION FOR ONCHANGE CITY TO GET TOWN LIST BASED ON SELECTED CITY
    public function get_towns(Request $request)
    {
        if ($request->ajax()) {
            $id_city = $request->id_city;
            $towns = Town::where('cities_id', '=', $id_city)->pluck('name', 'id');
            return Response::json($towns);
        }
    }

    //FUNCTION FOR ONCHANGE COMPANY TO GET COMPANY USER LIST BASED ON SELECTED COMPANY
    public function get_company_user(Request $request)
    {
        if ($request->ajax()) {
            $id_company = $request->id_company;
            $company_user = CompanyUser::where('companies_id', '=', $id_company)->pluck('name', 'id');
            $company_user->prepend('', 0);
            return Response::json($company_user);
        }
    }

     private function getCompanyUser($company_user_role)
    {
        switch($company_user_role)
        {
            //===================================================
            // Get company user list from the same company
            //===================================================
            case CompanyUserRole::CORPORATE_MANAGER :
                $companyID = auth()->user()->companies_id;
                $query = CompanyUser::where('companies_id', $companyID )->get();
            break;
            //===================================================

            //===================================================
            // Get company user list from the same company leader
            // with the leader itself
            //===================================================
            case CompanyUserRole::TEAM_MANAGER :
                $companyUserID = auth()->user()->id;
                $query = CompanyUser::whereHas( 'company_user_teams', function( Builder $sale ) use( $companyUserID ){
                    $sale->where( 'leader_id', $companyUserID  );
                })->orWhere('id', $companyUserID)->get();
            break;
            //===================================================

            //===================================================
            // Get company user list from the same company user id (1 list only)
            //===================================================
            case CompanyUserRole::SALES_STAFF :
                $companyUserID = auth()->user()->id;
                $query = CompanyUser::where('id', $companyUserID)->get();
            break;
            //===================================================
        }
        return $query;
    }
    //===================================================

    //FUNCTION FOR ONCHANGE COMPANY USER TO GET CUSTOMER DATA
    public function get_customer(Request $request){
        if($request->ajax()){
            $id_user = $request->id_user;
            $customer = Customer::where('company_users_id', '=', $id_user)->pluck('name', 'id');
            return Response::json( $customer );
        } 
    }

    public function create()
    {
        $data = new \stdClass;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $data->page_type = "create";
        $data->page_title = __('label.createNew') . ' ' . __('label.customers');
        $data->form_action = route('manage.customer.create.store');
        // ------------------------------------------------------------------
        
        $data->customer = factory( Customer::class )->state('init')->make();
        $desiredArea = factory( CustomerDesiredArea::class )->state('init')->make();

        $area = new \stdClass;
        $area->desiredArea = array( $desiredArea );
        $data->area = $area;

        $template = new \stdClass;
        $template->desiredArea = $desiredArea;
        $data->template = $template;

        $data->city = GroupLine::with(['cities' => function($q) {                
            $q->where('prefectures_id', '=', Prefecture::Miyagi_id)->orderBy('name_kana')->orderBy('group_line_id');
        }])->orderBy('id')->get();;

        $data->consider_amount  = ListConsiderAmount::get();
        $data->consider_land  = ListLandArea::get();

        //======================================================
        //GET company user based on user company roles
        //======================================================
        $user_roles = Auth::user()->company_user_roles_id;
        $user_id = Auth::user()->id;
        $user_company = Auth::user()->companies_id;

        $data->companyuser  = $this->getCompanyUser($user_roles);
        $data->customer->company_users_id  = $user_id;
        return view( 'manage.customer.create.index', (array) $data );
    }

    //FUNCTION FOR SAVE ALL FORM DATA
    public function store(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        // ------------------------------------------------------------------
        $dataset = json_decode( $request->dataset );
        
        if( !empty( $dataset->customer )){

            // Set Default Value
            if($dataset->customer->minimum_price=="" ) $dataset->customer->minimum_price = null;
            if($dataset->customer->maximum_price=="" )$dataset->customer->maximum_price = null;
            if($dataset->customer->minimum_price_land_area=="" ) $dataset->customer->minimum_price_land_area = null;
            if($dataset->customer->maximum_price_land_area=="" ) $dataset->customer->maximum_price_land_area = null;
            if($dataset->customer->minimum_land_area=="" ) $dataset->customer->minimum_land_area = null;
            if($dataset->customer->maximum_land_area=="" ) $dataset->customer->maximum_land_area = null;
            $dataset->customer->created_at = Carbon::now();
            $dataset->customer->update_at = Carbon::now();
            $dataset->customer->flag = 0;
            $dataset->customer->is_cancellation = 0;
            $dataset->customer->not_leave_record = 0;
            $dataset->customer->license = 1;
            // --------------------------------------------------------------

            $dataset->customer->password = bcrypt($dataset->customer->password);

            // --------------------------------------------------------------
            // Create the Customer
            // --------------------------------------------------------------
            $customer = Customer::create( (array) $dataset->customer );
            $response->customer = $customer; // Add to the JSON response
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create the publishing customers
            // --------------------------------------------------------------
            if( !empty( $dataset->area )){
                foreach( $dataset->area as $group ){
                    foreach( $group as $desiredArea ){
                        // --------------------------------------------------
                        $desiredArea->customers_id = $customer->id;
                        $desiredArea->created_at =  Carbon::now();
                        if($desiredArea->cities_area_id == ""){
                            $desiredArea->cities_area_id = null;
                        }
                        $area = CustomerDesiredArea::create( (array) $desiredArea );
                        // --------------------------------------------------
                    }
                }
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create history with action type = 1
            // --------------------------------------------------------------
            //$cust_log = new CustomerLogActivity();
            //$cust_log->customers_id = $customer->id;
            //$cust_log->action_types_id =ActionType::SIGN_UP;
            //$cust_log->ip = $request->ip();
            //$cust_log->access_time = Carbon::now();
            //$cust_log->save();

            //---------------------------------------------------
            //Save customer log activity when Register User Via Admin/Manage
            //---------------------------------------------------
            CustomerLogActivity::storeCustomerLog(ActionType::SIGN_UP, $customer->id, $request->ip());
            //---------------------------------------------------
            // ------------------------------------------------------------------

            
            // ----------------------------------------------------------------------
            // @ Function to store process of companyUserLogActivity
            // ----------------------------------------------------------------------
            $customerData = Customer::with('company_user')->find( $customer->id );
            $companyUserOrm = auth()->user();
            CompanyUserLogActivity::storeCompanyUserLog($companyUserOrm,CompanyUserLogActivity::CREATE_NEW_CUSTOMER, $customerData->toArray());
            // ----------------------------------------------------------------------
            
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        // ------------------------------------------------------------------
        
    }

    public function edit($id)
    {
        $data = new \stdClass;
        // ------------------------------------------------------------------
        $data->page_type = "edit";
        $data->page_title = __('label.edit') . ' ' . __('label.customers');
        $data->form_action = route('manage.customer.edit.update', $id);
        // ------------------------------------------------------------------
        
        $data->customer = Customer::where('customers.id', $id)->with('company_user')->first();
        $desiredAreaEmpty = factory( CustomerDesiredArea::class )->state('init')->make();

        $desiredArea =  CustomerDesiredArea::where('customers_id', $id)->get();

        $area = new \stdClass;
        $area->desiredArea = $desiredArea ;
        $data->area = $area;

        $template = new \stdClass;
        $template->desiredArea = $desiredAreaEmpty;
        $data->template = $template;

        $data->city = GroupLine::with(['cities' => function($q) {                
            $q->where('prefectures_id', '=', Prefecture::Miyagi_id)->orderBy('name_kana')->orderBy('group_line_id');
        }])->orderBy('id')->get();;

        $data->consider_amount  = ListConsiderAmount::get();
        $data->consider_land  = ListLandArea::get();

        //======================================================
        //GET company user based on user company roles
        //======================================================
        $user_roles = Auth::user()->company_user_roles_id;
        $user_id = Auth::user()->id;
        $user_company = Auth::user()->companies_id;
        $data->companyuser  = $this->getCompanyUser($user_roles);
        $data->customer->company_users_id  = $user_id;

        return view( 'manage.customer.edit.index', (array) $data );
    }

    public function update(Request $request, $id)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        // ------------------------------------------------------------------

        // --------------------------------------------------------------
        // get property data from request
        // --------------------------------------------------------------
        $dataset = json_decode( $request->dataset, true );
        $customer_data = $dataset['customer'];
        //dd($dataset);
        
        if($customer_data['minimum_price']=="" ) $customer_data['minimum_price'] = null;
        if($customer_data['maximum_price']=="" ) $customer_data['maximum_price'] = null;
        if($customer_data['minimum_price_land_area']=="" ) $customer_data['minimum_price_land_area'] = null;
        if($customer_data['maximum_price_land_area']=="" ) $customer_data['maximum_price_land_area'] = null;
        if($customer_data['minimum_land_area']=="" ) $customer_data['minimum_land_area'] = null;
        if($customer_data['maximum_land_area']=="" ) $customer_data['maximum_land_area'] = null;

        // --------------------------------------------------------------
        //Check if Customer Exist
        // --------------------------------------------------------------
        $customer = Customer::findOrFail($id);
        // --------------------------------------------------------------

        // --------------------------------------------------------------
        //Check password if need to modify
        // --------------------------------------------------------------
        $customer_data['password']   = !empty($dataset['password']) ? $dataset['password'] : $customer['password'];
        if (Hash::needsRehash($customer_data['password'])) {
            $customer_data['password'] = bcrypt($customer_data['password']);
        }
        // --------------------------------------------------------------

        $customer_data['updated_at'] = Carbon::now();

        // --------------------------------------------------------------
        //insert into customer log activity if licesen change to 停止
        // --------------------------------------------------------------
        if ($customer['license'] <> $customer_data['license'] && $customer_data['license'] == 0) {
            //$cust_log = new CustomerLogActivity();
            //$cust_log->customers_id = $id;
            //$cust_log->action_types_id = ActionType::SUSPENSION_OF_USE;
            //$cust_log->ip = $request->ip();
            //$cust_log->access_time = Carbon::now();
            //$cust_log->save();

        
            //---------------------------------------------------
            //Save customer log activity when Register User Via Admin/Manage
            //---------------------------------------------------
            CustomerLogActivity::storeCustomerLog(ActionType::SUSPENSION_OF_USE, $id, $request->ip());
            //---------------------------------------------------
        }
        // --------------------------------------------------------------

        // --------------------------------------------------------------
        // UPDATE THE DATA
        // --------------------------------------------------------------
        $customer->update( $customer_data );
        $response->customer = $customer; // Add to the JSON response
        // --------------------------------------------------------------

        // --------------------------------------------------------------
        //DELETE DESIRED AREA FIRST BEFORE RE ENTERING
        // --------------------------------------------------------------
        $item = CustomerDesiredArea::where('customers_id', $id);
        $item->delete();
        // --------------------------------------------------------------

        // --------------------------------------------------------------
        // Create Desired Area
        // --------------------------------------------------------------
       
        if( !empty( $dataset['area'] )){
            foreach( $dataset['area'] as $group ){
                foreach( $group as $desiredArea ){
                    // --------------------------------------------------
                    $desiredArea['customers_id'] = $customer['id'];
                    $desiredArea['created_at'] =  Carbon::now();
                    if($desiredArea['cities_area_id'] == ""){
                        $desiredArea['cities_area_id'] = null;
                    }
                    CustomerDesiredArea::create( (array) $desiredArea );
                    // --------------------------------------------------
                }
            }
        }
        // --------------------------------------------------------------

        // ------------------------------------------------------------------
        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        // ------------------------------------------------------------------
    }

    public function destroy($id)
    {
        $item = Company::findOrFail($id);
        $item->delete();

        $this->saveLog('Delete Company', 'Delete Company, Name : ' . $item->company_name . '', Auth::user()->id);

        return 1;
    }


    // ----------------------------------------------------------------------
    // Flag toggle
    // ----------------------------------------------------------------------
    public function flag( $id ){
        $response = new \stdClass;
        $response->status = 'success';

        $customer = Customer::find( $id );
        if( !$customer ) $response->status = 'error';
        else {
            $customer->flag = !$customer->flag;
            $customer->save();
            $response->result = $customer->flag;
        }

        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
    }
    // ----------------------------------------------------------------------
}
