<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Helpers\DatatablesHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;
use App\Models\CompanyUserLogActivity;
use App\Models\ActionType;

use App\Traits\LogActivityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class CompanyUserController extends Controller
{
    use LogActivityTrait;

    public function __construct()
    {
        //construct
    }

    protected function validator(array $data, $type)
    {
        return Validator::make($data, [
            'name'                    => 'required|string|max:50',
            'email'                   => 'required|email|max:255|unique:company_users,email' . ($type == 'update' ? ',' . $data['id'] : ''),
            'password'                => $type == 'create' ? 'required|string|min:8|max:255' : 'string|min:8|max:255',
            'company_user_roles_id'   => 'required',
        ]);
    }

    /**
     * @param string parameter - url of custom resources page
     *
     * @return string - Any
     *
     * [AJax from Data Table : Defined "DataTable(..." method on backend/_base/content_datatables.blade.php ]
     * You may add custom page/api/route, this wrapped middleware as well
     */
    public function show($id, $param)
    {
        //if ($param == 'json') {
        //    $model = CompanyUser::with('company_user_role');
        //    return (new DatatablesHelper)->instance($model)->toJson();
        //}
        //abort(404);
    }

    public function index($id)
    {
        $data['page_title']     = __('label.view_user_list');
        //$data['card_title']     = '';
        $data['page_type']      = 'detail';
        $data['id']             = $id;

        $data['users'] = CompanyUser::where('companies_id',$id)->with('company')->first();
        $data['roles'] = CompanyUserRole::all();

        return view('backend.companyuser.index', $data);
    }

    //===================================================
    //B18 COMPANY USER CREATE
    //===================================================
    public function create($id){
        $data['company']            = Company::where('companies.id', $id)->first();
        $data['item']               = new CompanyUser();
        $data['id']                 = $id;
        $data['item']->company_user_role_options = CompanyUserRole::pluck('label', 'id');

        $data['page_title']         = __('label.create_new_user');
        $data['form_action']        = route('admin.company.user.store', $id);
        $data['page_type']          = 'create';

        return view('backend.companyuser.form', $data);
    }

    public function store($id, Request $request)
    {
        $data = $request->all();
        $this->validator($data, 'create')->validate();

        $data['password']       = bcrypt($data['password']);
        $data['companies_id'] = $id;

        // Store Company
        $new = new CompanyUser();
        $new->fill($data)->save();

        return redirect()->route('admin.company.user.create', $id)->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
    }

    //===================================================
    //B19 COMPANY USER CREATE
    //===================================================
    public function edit($id,$id_user){
        $data['company']               = Company::where('companies.id', $id)->first();

        $data['id']             = $id;
        $data['item']           = CompanyUser::with('company_user_role')->where('company_users.id', $id_user)->first();
        $data['item']->company_user_role_options = CompanyUserRole::pluck('label', 'id');

        $data['page_title']     = __('label.user_detail');
        $data['form_action']    = route('admin.company.user.update', [$id,$id_user]);
        $data['page_type']      = 'edit';

        return view('backend.companyuser.form', $data);
    }

    public function update(Request $request, $id, $id_user)
    {
        $data                       = $request->all();
        $currentCompanyUser         = CompanyUser::find($id_user);

        $data['id']                 = $id_user;
        $data['companies_id']       = $id;
        $data['password']           = !empty($data['password']) ? bcrypt($data['password']) : $currentCompanyUser['password'];

        $this->validator($data, 'update')->validate();

        $currentCompanyUser->update($data);

        return redirect()->route('admin.company.user.edit', [$id, $id_user])->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
    }

    public function destroy($id)
    {
        $item = CompanyUser::findOrFail($id);
        $item->delete();

        //$this->saveLog('Delete Company', 'Delete Company, Name : ' . $item->company_name . '', Auth::user()->id);

        return 1;
    }

    //===================================================
    //B17 SEARCH HISTORY ALL
    //===================================================
    public function filter($id, Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $relations = collect([ 'role', 'latestLogActivities' ]);
        $query = CompanyUser::select('*')->where('companies_id', '=', $id);


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

    //===================================================
    //B29 COMPANY USER LOG
    //===================================================
    public function user_log()
    {
        $data['page_title']     = __('label.search_user_usage_history');
        $data['page_type']      = 'detail';

        $data['roles'] = CompanyUserRole::all();
        $data['actions'] = ActionType::all();

        return view('backend.companyuser.userlog.index', $data);
    }

    public function user_log_filter(Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        $relations = collect([ 'company_user', 'company_user.company', 'company_user.role' ]);
        $query = CompanyUserLogActivity::select('*');


        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // ------------------------------------------------------------------
        // START OTHER filter
        // ------------------------------------------------------------------
        if (!empty($filter->datestart)) {
            $start = Carbon::parse( $filter->datestart );
            $query = $query->whereDate( 'access_time', '>=', $start );
        }
        if (!empty($filter->datefinish)) {
            $end = Carbon::parse( $filter->datefinish );
            $query = $query->whereDate( 'access_time', '<=', $end );
        }

        if( !empty( $filter->name)){
            $name = $filter->name;
            $query->whereHas( 'company_user.company', function( Builder $sale ) use( $name ){
                $sale->where('company_name', 'LIKE', "%{$name}%");
            });
        }

        if( !empty( $filter->username)){
            $username = $filter->username;
            $query->whereHas( 'company_user', function( Builder $sale ) use( $username ){
                $sale->where('name', 'LIKE', "%{$username}%");
            });
        }

        if( !empty( $filter->email)){
            $email = $filter->email;
            $query->whereHas( 'company_user', function( Builder $sale ) use( $email ){
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
        $orders = ['access_time', 'company_user.company.company_name', 'company_user.name', 'company_user.email', 'company_user.role.label', 'activity'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            $order = null;
            $direction = $filter->direction ?? 'asc';

            $order = $filter->order;
            if($order == 'company_user.name'){
               $query = $query->orderBy( 
                    CompanyUser::select( 'name' )
                        ->whereColumn( 'company_users.id', 'company_user_log_activities.company_users_id' ),
                    $direction
                );

            }elseif($order == 'company_user.email'){
               $query = $query->orderBy( 
                    CompanyUser::select( 'email' )
                        ->whereColumn( 'company_users.id', 'company_user_log_activities.company_users_id' ),
                    $direction
                );

            }elseif($order == 'company_user.company.company_name'){
               $query = $query->join('company_users', 'company_user_log_activities.company_users_id', '=', 'company_users.id')
                                ->join('companies', 'company_users.companies_id', 'companies.id')
                                ->select('company_user_log_activities.*')
                                ->orderBy('companies.company_name', $direction);

            }elseif($order == 'company_user.role.label'){
               $query = $query->join('company_users', 'company_user_log_activities.company_users_id', '=', 'company_users.id')
                                ->join('company_user_roles', 'company_users.company_user_roles_id', 'company_user_roles.id')
                                ->select('company_user_log_activities.*')
                                ->orderBy('company_user_roles.label', $direction);

            }elseif( $order ){
                $query = $query->orderBy( $order, $direction );
            }; // Everything else
        }
        // ------------------------------------------------------------------


        $query->with($relations->unique()->all());

        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }


}
