<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Models\ActionType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\CustomerContactUs;
use App\Models\ContactUsType;
use App\Models\Customer;
use App\Models\Company;
use App\Models\CustomerLogActivity;
use Illuminate\Support\Facades\Log;
use Throwable;

class ContactusController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'contact_us_types_id'  => 'required',
            'text'                 => 'required|string',
        ]);
    }

    public function index()
    {
        $data = new \stdClass;
        $data->contact_us = factory(CustomerContactUs::class)->state('init')->make();
        $data->contact_type = ContactUsType::get();
        $data->title = __('label.inquiries');
        return view('frontend.inquiry.index', (array) $data);
    }

    public function store(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        $response->status = 'success';
        $dataset = json_decode($request->dataset);
        // ------------------------------------------------------------------

        try {

            if (Auth::guard('user')->check()) {
                // ------------------------------------------------------------------
                // Find Customer and Company
                // ------------------------------------------------------------------
                $id                 = Auth::guard('user')->user()->id;
                $currentCustomer    = Customer::with('company_user')->find($id);
                $company_id         = $currentCustomer->company_user->companies_id;
                $currentCompany     = Company::find($company_id);
                // ------------------------------------------------------------------

                // ------------------------------------------------------------------
                // Set Default Value
                // ------------------------------------------------------------------
                $dataset->contactUs->customers_id       = $id;
                $dataset->contactUs->person_in_charge   = $currentCustomer->company_user->name;
                $dataset->contactUs->company_name       = $currentCompany->company_name;
                $dataset->contactUs->name               = $currentCustomer->name;
                $dataset->contactUs->email              = $currentCustomer->email;
            }

            $dataset->contactUs->flag               = false;
            $dataset->contactUs->is_finish          = false;
            $dataset->contactUs->created_at         = Carbon::now();
            $dataset->contactUs->updated_at         = Carbon::now();
            // ------------------------------------------------------------------

            // ------------------------------------------------------------------
            // Debugging & Validating
            // ------------------------------------------------------------------
            //dd($dataset);
            //$this->validator($data)->validate();
            // ------------------------------------------------------------------

            // --------------------------------------------------------------
            // Create the CustomerContactUs
            // --------------------------------------------------------------
            $CustomerContactUs = CustomerContactUs::create((array) $dataset->contactUs);
            $response->CustomerContactUs = $CustomerContactUs; // Add to the JSON response
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Create the CustomerContactUs
            // --------------------------------------------------------------
            if (Auth::guard('user')->check()) {
                //$notleave           = $currentCustomer->not_leave_record;
                //if(!$notleave){
                //$log['action_types_id'] = 6;
                //$log['customers_id']    = $id;
                //$log['ip']              = $request->ip();
                //$log['access_time']     = Carbon::now();
                //$savelog = new CustomerLogActivity();
                //$savelog->fill($log)->save();
                //}

                //---------------------------------------------------
                //Save customer log activity when Sending Contact Us
                //---------------------------------------------------
                CustomerLogActivity::storeCustomerLog(ActionType::CONTACT_US, $id, $request->ip());
                //---------------------------------------------------
            }
            // --------------------------------------------------------------

            // ------------------------------------------------------------------
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            // ------------------------------------------------------------------

        } catch (Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Frontend/ContactUsController (store Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Frontend/ContactUsController (store Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = 'failed';
            $response->message = $e;

            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }
}
