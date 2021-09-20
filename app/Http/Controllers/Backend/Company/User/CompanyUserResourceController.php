<?php

namespace App\Http\Controllers\Backend\Company\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;

use App\Traits\LogActivityTrait;

use Auth;
use App\Models\CompanyUserLogActivity;

class CompanyUserResourceController extends Controller
{
    use LogActivityTrait;

    protected function validator(array $data, $type)
    {
        return Validator::make($data, [
            'name'                    => 'required|string|max:50',
            'email'                   => 'required|email|max:255|unique:company_users,email' . ($type == 'update' ? ',' . $data['id'] : ''),
            'password'                => $type == 'create' ? 'required|string|min:8|max:255' : 'string|min:8|max:255',
            'company_user_roles_id'   => 'required',
        ]);
    }

    //===================================================
    //B18 COMPANY USER CREATE
    //===================================================
    public function create($companyID)
    {
        $data['company']            = Company::where('companies.id', $companyID)->first();
        $data['item']               = new CompanyUser();
        $data['id']                 = $companyID;
        $data['item']->company_user_role_options = CompanyUserRole::pluck('label', 'id');

        $data['page_title']         = __('label.create_new_user');
        $data['form_action']        = route('admin.company.user.store', $companyID);
        $data['page_type']          = 'create';

        return view('backend.company.user.form.index', $data);
    }

    public function store($companyID, Request $request)
    {
        $data = $request->all();
        $this->validator($data, 'create')->validate();

        $data['password']       = bcrypt($data['password']);
        $data['companies_id'] = $companyID;
        $data['is_active'] = 1;

        // Store Company
        $newUser = new CompanyUser();
        $newUser->fill($data)->save();

        // ----------------------------------------------------------------------
        // @ Function to store process of companyUserLogActivity
        // ----------------------------------------------------------------------
        CompanyUserLogActivity::storeCompanyUserLog($newUser, CompanyUserLogActivity::BE_CREATED, $newUser);
        // ----------------------------------------------------------------------

        return redirect()->route('admin.company.user.list', $companyID)->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
    }

    //===================================================
    //B19 COMPANY USER CREATE
    //===================================================
    public function edit($companyID, $userID)
    {
        $data['company']               = Company::where('companies.id', $companyID)->first();

        $data['id']             = $companyID;
        $data['item']           = CompanyUser::with('company_user_role')->where('company_users.id', $userID)->first();
        $data['item']->company_user_role_options = CompanyUserRole::pluck('label', 'id');

        $data['page_title']     = __('label.user_detail');
        $data['form_action']    = route('admin.company.user.update', [$companyID, $userID]);
        $data['page_type']      = 'edit';

        return view('backend.company.user.form.index', $data);
    }

    public function update(Request $request, $companyID, $userID)
    {
        $data                       = $request->all();
        $currentCompanyUser         = CompanyUser::find($userID);

        $data['id']                 = $userID;
        $data['companies_id']       = $companyID;
        $data['password']           = !empty($data['password']) ? bcrypt($data['password']) : $currentCompanyUser['password'];

        $this->validator($data, 'update')->validate();

        $beforeUpdate = $currentCompanyUser->getOriginal();

        $currentCompanyUser->update($data);

        // ----------------------------------------------------------------------
        // @ Function to store process of companyUserLogActivity
        // ----------------------------------------------------------------------
        if ($currentCompanyUser->is_active != 1) {
            CompanyUserLogActivity::storeCompanyUserLog($currentCompanyUser, CompanyUserLogActivity::BE_STOPPED, $currentCompanyUser);
        }
        // ----------------------------------------------------------------------

        // ----------------------------------------------------------------------
        // @ Function to store process of companyUserLogActivity
        // ----------------------------------------------------------------------
        CompanyUserLogActivity::storeCompanyUserLog($currentCompanyUser, CompanyUserLogActivity::CHANGE_USER_INFO, ["before" => $beforeUpdate,  "after" => $currentCompanyUser->toArray()]);
        // ----------------------------------------------------------------------

        return redirect()->route('admin.company.user.list', [$companyID, $userID])->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
    }
}
