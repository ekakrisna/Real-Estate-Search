<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\Log;
use Throwable;

class AccountSettingController extends Controller
{
    protected function validator(array $data, $type)
    {
        return Validator::make($data, [
            'phone'                  => 'required|max:20|min:5',
            'company_users_id'       => 'required',
        ]);
    }

    public function index()
    {
        $id = Auth::guard('user')->user()->id;
        $data['customer_detail'] = Customer::where('id', $id)->first();

        $company_user_id = $data['customer_detail']->company_users_id;

        if ($company_user_id ==  null) {
            $data['company_user'] = null;
            $data['company_user_list'] = CompanyUser::with('company')->get();
        } else {
            $data['company_user'] = CompanyUser::where('id', $company_user_id)->first();

            $company_id = $data['company_user']->companies_id;
            $data['company_user_list'] = CompanyUser::where('companies_id', $company_id)->with('company')->get();
        }

        $data['title'] =  __('label.AccountSetting');
        return view('frontend.account_setting.index', $data);
    }

    public function edit(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        // ------------------------------------------------------------------
        try {
            $response->status = 'success';
            $dataset = json_decode($request->dataset, true);
            $customer_data = $dataset['customer'];

            //dd($dataset);

            // Set Default Value
            $customer_data['updated_at'] = Carbon::now();

            // --------------------------------------------------------------
            //Check if Customer Exist
            // --------------------------------------------------------------
            $customer = Customer::findOrFail($customer_data['id']);
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // UPDATE THE DATA
            // --------------------------------------------------------------
            $customer->update($customer_data);
            $response->customer = $customer; // Add to the JSON response
            // --------------------------------------------------------------

            // ------------------------------------------------------------------
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            // ------------------------------------------------------------------

        } catch (Throwable $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Frontend/AccountSettingController (edit Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = 'failed';
            $response->message = $e;

            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }

    public function cancel(Request $request)
    {
        // ------------------------------------------------------------------
        $response = new \stdClass;
        // ------------------------------------------------------------------
        try {
            $response->status = 'success';
            $dataset = json_decode($request->dataset, true);

            $customer_data['id'] = $dataset['customer']['id'];
            // Set Default Value
            $customer_data['updated_at'] = Carbon::now();
            $customer_data['is_cancellation'] = true;

            //dd($customer_data);

            // --------------------------------------------------------------
            //Check if Customer Exist
            // --------------------------------------------------------------
            $customer = Customer::findOrFail($customer_data['id']);
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // UPDATE THE DATA
            // --------------------------------------------------------------
            $customer->update($customer_data);
            $response->customer = $customer; // Add to the JSON response
            // --------------------------------------------------------------

            // ------------------------------------------------------------------
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            // ------------------------------------------------------------------

        } catch (Throwable $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Frontend/AccountSettingController (cancel Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = 'failed';
            $response->message = $e;

            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }
}
