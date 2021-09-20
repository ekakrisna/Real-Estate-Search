<?php

namespace App\Http\Controllers\Manage\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Carbon\Carbon;

use App\Models\Customer;
use App\Models\CustomerLogActivity;
use App\Models\CustomerDesiredArea;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CustomerSearchHistory;
use App\Models\CustomerContactUs;
use App\Models\CustomerFavoriteProperty;

class CustomerDetailController extends Controller
{
    //===================================================
    //A5 CUSTOMER DETAIL
    //===================================================
    public function index($id)
    {
        $data['page_title']     = __('label.customers_detail');
        $data['card_title']     = __('label.customers_detail');
        $data['id']     = $id;

        //Customer Detail B5-1 Section
        $data['customer_detail']            = Customer::where('id', $id)->with('company_user')->first();

        $id_company = $data['customer_detail']->company_user->companies_id;
        $data['customer_company']           = Company::where('id', $id_company)->first();

        //CUSTOMER DESIRED AREA FOR B5-1-9 Section
        $data['customer_desired_area']           = CustomerDesiredArea::where('customers_id', $id)->with('town')->with('city')->get();
        //CUSTOMER Last use date FOR B5-1-17 Section
        $data['customer_last_use_date']          = CustomerLogActivity::where('customers_id', $id)->where('action_types_id', '=', 4)->orderBy('id', 'desc')->first();
        //CUSTOMER Favourite area FOR B5-1-18 Section
        $data['customer_favorite_properties_count'] = CustomerFavoriteProperty::where('customers_id', $id)->count();
        //Customer last viewed FOR B5-1-20 Section
        $data['property_checked_count']             = CustomerLogActivity::where('customers_id', $id)->where('action_types_id', '=', 4)->where('access_time', '>=', Carbon::now()->subDays(14)->toDateTimeString())->count();

        // B5-2 Part
        $data['customer_log']  = CustomerLogActivity::where('customers_id', $id)->with(['action_type','property','customer_favorite_property'])->orderBy('id', 'desc')->take(5)->get();

        // B5-3 Part
        $data['customer_search_histories']  = CustomerSearchHistory::where('customers_id', $id)->orderBy('id', 'desc')->take(5)->get();

        // B5-4 Part
        //$data['customers_contact_us']       = CustomerContactUs::where('customers_id', $id)->with('property')->orderBy('id', 'desc')->take(5)->get();

        return view('manage.customer.detail.index', $data);
    }
}
