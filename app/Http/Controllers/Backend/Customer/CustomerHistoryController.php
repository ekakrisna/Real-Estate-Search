<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Http\Controllers\Controller;
use App\Models\ActionType;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Customer;
use App\Models\CustomerLogActivity;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Action;

class CustomerHistoryController extends Controller
{
    public function index()
    {
        $data = new \stdClass;
        $data->page_title = __('label.history_customer_search');
        $data->CustomerContactUs = CustomerLogActivity::with(['action_type', 'customer.company_user.company', 'property', 'customer_favorite_property'])->get();
        $data->ActionType = ActionType::all();
        $data->Company = Company::all();
        $data->CompanyUser = CompanyUser::all();
        return view('backend.customer.all_use_history.index', (array) $data);
    }

    public function changeContactFlag($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->flag = !$customer->flag;
        if ($customer->flag) {
            $flag = 1;
        } else {
            $flag = 0;
        }
        $customer->save();

        return response()->json(['status' => 200, 'flag' => $flag]);
    }

    public function filter(Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');
        // Default configuration
        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];
        // Base query
        $relations = collect(['action_type', 'customer.company_user.company', 'property', 'customer_favorite_property']);
        $query = CustomerLogActivity::select('*')->orderBy('access_time', 'desc');
        // Add the relations, make sure they are unique
        $query->with($relations->unique()->all());

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // Minimum - Max date filter
        if (!empty($filter->datestart)) {
            $query->whereDate('access_time', '>=', $filter->datestart);
        }
        if (!empty($filter->datefinish)) {
            $query->whereDate('access_time', '<=', $filter->datefinish);
        }

        // name search filter
        if (!empty($filter->search)) {
            $keywords = preg_replace('/\s+/', ' ', $filter->search);
            $keywords = explode(' ', trim($keywords));
            $query->where(function ($query) use ($filter) {
                $keywords = preg_replace('/\s+/', ' ', $filter->search);
                $keywords = explode(' ', trim($keywords));
                // Search user
                $query->where(function ($query) use ($keywords) {
                    if (!empty($keywords)) foreach ($keywords as $keyword) {
                        $query->whereHas('customer', function ($query) use ($keyword) {
                            $query->where('name', 'LIKE', "%{$keyword}%");
                        });
                    }
                });
            });
        }

        // location search filter
        if (!empty($filter->location)) {
            $query->where(function ($query) use ($filter) {
                $keywords = preg_replace('/\s+/', ' ', $filter->location);
                $keywords = explode(' ', trim($keywords));
                $query->where(function ($query) use ($keywords) {
                    if (!empty($keywords)) foreach ($keywords as $keyword) {
                        $query->whereHas('property', function ($query) use ($keyword) {
                            $query->where('location', 'LIKE', "%{$keyword}%");
                        });
                    }
                });
            });
        }

        // action status filter
        if (!empty($filter->status)) {
            $actionType = (int) $filter->status;
            $query->whereHas('action_type', function (Builder $sale) use ($actionType) {
                $sale->where('id', $actionType);
            });
        }

        // Corporate in charge
        if (!empty($filter->corporate)) {
            $corporateID = (int) $filter->corporate;
            $query->whereHas('customer.company_user.company', function (Builder $sale) use ($corporateID) {
                $sale->where('id', $corporateID);
            });
        }

        // Person in charge
        if (!empty($filter->person)) {
            $personID = (int) $filter->person;
            $query->whereHas('customer.company_user', function (Builder $sale) use ($personID) {
                $sale->where('id', $personID);
            });
        }

        $orders = ['name', 'location', 'access_time', 'building', 'company', 'customer', 'action', 'property_id'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            $order = null;
            $direction = $filter->direction ?? 'asc';
            $order = $filter->order;
            if ($order == 'location') {
                $query = $query->orderBy(
                    Property::select('location')
                        ->whereColumn('properties.id', 'customer_log_activities.properties_id'),
                    $direction
                );
            } elseif ($order == 'name') {
                $query = $query->orderBy(
                    Customer::select('name')
                        ->whereColumn('customers.id', 'customer_log_activities.customers_id'),
                    $direction
                );
            } elseif ($order == 'building') {
                $query = $query->orderBy(
                    Property::select('building_conditions_desc')
                        ->whereColumn('properties.id', 'customer_log_activities.properties_id'),
                    $direction
                );
            } elseif ($order == 'action') {
                $query = $query->orderBy(
                    ActionType::select('label')
                        ->whereColumn('id', 'customer_log_activities.action_types_id'),
                    $direction
                );
            } elseif ($order == 'property_id') {
                $query = $query->orderBy(
                    Property::select('id')
                        ->whereColumn('id', 'customer_log_activities.properties_id'),
                    $direction
                );
            } elseif ($order == 'customer') {
                // customer.company_user.company
                $query = $query->join('customers', 'customer_log_activities.customers_id', '=', 'customers.id')
                    ->join('company_users', 'customers.company_users_id', '=', 'company_users.id')
                    ->select('customer_log_activities.*')
                    ->orderBy('company_users.name', $direction);
            } elseif ($order == 'company') {
                $query = $query->join('customers', 'customer_log_activities.customers_id', '=', 'customers.id')
                    ->join('company_users', 'customers.company_users_id', '=', 'company_users.id')
                    ->join('companies', 'company_users.companies_id', 'companies.id')
                    ->select('customer_log_activities.*')
                    ->orderBy('companies.company_name', $direction);
            } elseif ($order) {
                $query = $query->orderBy($order, $direction);
            }
            // --------------------------------------------------------------
        } else {
            // Default order!
            $query = $query->orderBy('access_time', 'desc');
        }

        if (!empty($filter->build)) {
            $building = $filter->build;
            $query->whereHas('property', function (Builder $sale) use ($building) {
                $sale->where('building_conditions_desc', 'LIKE', "%{$building}%");
            });
        }

        // Paginate the result
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
}
