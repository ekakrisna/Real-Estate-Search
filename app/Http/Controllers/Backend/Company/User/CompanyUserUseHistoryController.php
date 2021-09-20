<?php

namespace App\Http\Controllers\Backend\Company\User;

use Carbon\Carbon;
use App\Helpers\DatatablesHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyRole;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;
use App\Models\CompanyUserLogActivity;
use App\Models\ActionType;

use App\Traits\LogActivityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class CompanyUserUseHistoryController extends Controller
{
    use LogActivityTrait;
    //===================================================
    //B29 COMPANY USER LOG
    //===================================================
    public function index()
    {
        $data['page_title']     = __('label.usage_history_home_maker');
        $data['page_type']      = 'detail';

        $data['roles'] = CompanyUserRole::all();
        $data['actions'] = ActionType::all();

        return view('backend.company.user.log.index', $data);
    }

    public function filter(Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $relations = collect(['company_user', 'company_user.company', 'company_user.role']);
        $query = CompanyUserLogActivity::select('*');

        //FILTER ONLY USER WITH HOMEMAKER RULE / COMPANIES WITH ROLE 2
        $comp_id = CompanyRole::ROLE_HOME_MAKER;
        $query->whereHas('company_user.company', function (Builder $sale) use ($comp_id) {
            $sale->where('company_roles_id', '=', $comp_id);
        });

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // ------------------------------------------------------------------
        // START OTHER filter
        // ------------------------------------------------------------------
        if (!empty($filter->datestart)) {
            $start = Carbon::parse($filter->datestart);
            $query = $query->whereDate('access_time', '>=', $start);
        }
        if (!empty($filter->datefinish)) {
            $end = Carbon::parse($filter->datefinish);
            $query = $query->whereDate('access_time', '<=', $end);
        }

        if (!empty($filter->name)) {
            $name = $filter->name;
            $query->whereHas('company_user.company', function (Builder $sale) use ($name) {
                $sale->where('company_name', 'LIKE', "%{$name}%");
            });
        }

        if (!empty($filter->username)) {
            $username = $filter->username;
            $query->whereHas('company_user', function (Builder $sale) use ($username) {
                $sale->where('name', 'LIKE', "%{$username}%");
            });
        }

        if (!empty($filter->email)) {
            $email = $filter->email;
            $query->whereHas('company_user', function (Builder $sale) use ($email) {
                $sale->where('email', 'LIKE', "%{$email}%");
            });
        }

        if (!empty($filter->authority)) {
            $authority = (int) $filter->authority;
            $query->whereHas('company_user.role', function (Builder $sale) use ($authority) {
                $sale->where('id', $authority);
            });
        }

        if (!empty($filter->action)) {
            $query->where('activity', 'LIKE', "%{$filter->action}%");
        }

        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = ['access_time', 'company_name', 'user_name', 'email', 'authority', 'activity'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            $order = null;
            $direction = $filter->direction ?? 'asc';

            $order = $filter->order;
            if ($order == 'user_name') {
                $query = $query->orderBy(
                    CompanyUser::select('name')
                        ->whereColumn('company_users.id', 'company_user_log_activities.company_users_id'),
                    $direction
                );
            } elseif ($order == 'email') {
                $query = $query->orderBy(
                    CompanyUser::select('email')
                        ->whereColumn('company_users.id', 'company_user_log_activities.company_users_id'),
                    $direction
                );
            } elseif ($order == 'company_name') {
                $query = $query->join('company_users', 'company_user_log_activities.company_users_id', '=', 'company_users.id')
                    ->join('companies', 'company_users.companies_id', 'companies.id')
                    ->select('company_user_log_activities.*')
                    ->orderBy('companies.company_name', $direction);
            } elseif ($order == 'authority') {
                $query = $query->join('company_users', 'company_user_log_activities.company_users_id', '=', 'company_users.id')
                    ->join('company_user_roles', 'company_users.company_user_roles_id', 'company_user_roles.id')
                    ->select('company_user_log_activities.*')
                    ->orderBy('company_user_roles.label', $direction);
            } elseif ($order) {
                $query = $query->orderBy($order, $direction);
            }; // Everything else
        } else {
            // Default order!
            $query = $query->orderBy('access_time', 'desc');
        }
        // ------------------------------------------------------------------


        $query->with($relations->unique()->all());

        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }
}
