<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerLogActivity;
use App\Models\CustomerFavoriteProperty;
use App\Models\CustomerSearchHistory;
use App\Models\CustomerContactUs;
use App\Models\Customer;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $data['page_title']     = __('label.dashboard');

        // Get B1-1 Part
        $data['customer_log_activites']             = CustomerLogActivity::with(['customer', 'action_type', 'customer.favorite_properties', 'customer.properties_checked', 'customer.company_user.company'])->orderBy('id', 'desc')->take(5)->get();

        // Get B1-2 Part
        $data['customer_search_histories']  = CustomerSearchHistory::with(['customer.company_user.company'])->orderBy('id', 'desc')->take(5)->get();

        // Get B1-3 Part 
        $data['customers_contact_us']       = CustomerContactUs::with('customer.company_user.company')->orderBy('id', 'desc')->take(5)->get();

        return view('backend.dashboard.index', $data);
    }

    public function changeCustomerFlag($id)
    {        
        $customer = Customer::findOrFail($id);
        $customer->flag = !$customer->flag;
        $customer->save();

        $flag = (int) $customer->flag;
        return response()->json(['status' => 200, 'flag' => $flag ]);
    }

    public function changeCustomerContactUsFlag($id)
    {
        $customerContactUs = CustomerContactUs::findOrFail($id);
        $customerContactUs->flag = !$customerContactUs->flag;
        if ($customerContactUs->flag) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $customerContactUs->save();

        return response()->json(['status' => 200, 'flag' => $flag]);
    }
}
