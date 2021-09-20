<?php

namespace App\Http\Controllers\Backend\Company\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Traits\LogActivityTrait;

use App\Models\UserRole;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;
use App\Models\CompanyUserLogActivity;


class CompanyUserListController extends Controller
{
    use LogActivityTrait;

    public function index( $company )
    {
        $company_model = Company::find( $company );
        $data['page_title']     = $company_model->company_name.' '. __('label.user_management_of');
        //$data['card_title']     = '';
        $data['page_type']      = 'detail';
        $data['id']             = $company;

        $data['roles'] = CompanyUserRole::all();
        $data['company'] =  $company_model;

        return view('backend.company.user.index.index', $data );
    }

    //===================================================
    //B17 SEARCH HISTORY ALL
    //===================================================
    public function filter( $company, Request $request )
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $relations = collect([ 'role', 'latestLogActivities' ]);
        $query = CompanyUser::select('*')->where('companies_id', '=', $company);


        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // ------------------------------------------------------------------
        // START OTHER filter
        // ------------------------------------------------------------------

        if (!empty($filter->name)) {
            $query->Where('name', 'LIKE', "%{$filter->name}%");
        }

        if (!empty($filter->email)) {
            $query->Where('email', 'LIKE', "%{$filter->email}%");
        }

        if (!empty($filter->type)) {
            $query->where('company_user_roles_id', '=', (int) $filter->type);
        }

        if (!empty($filter->license)) {
            $query->where('is_active', '=', (int) $filter->license);
        }

        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = ['company_users.name', 'company_users.email', 'company_users.created_at', 'access_time', 'company_users.is_active'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            $order = null;
            $direction = $filter->direction ?? 'asc';

            $order = $filter->order;
            if($order == 'access_time'){
                $query = $query->orderBy( 
                    CompanyUserLogActivity::select( 'access_time' )
                        ->whereColumn( 'company_users.id', 'company_user_log_activities.company_users_id' ), $direction);

            }elseif( $order ){
                $query = $query->orderBy( $order, $direction );
            }; // Everything else
        }
        // ------------------------------------------------------------------


        $query->withCount('customer')->with($relations->unique()->all());

        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }

    public function destroy( $company )
    {
        $item = CompanyUser::findOrFail( $company );
        $item->delete();

        //$this->saveLog('Delete Company', 'Delete Company, Name : ' . $item->company_name . '', Auth::user()->id);

        return 1;
    }

}
