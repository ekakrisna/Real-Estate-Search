<?php

namespace App\Http\Controllers\Backend\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyRole;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompanyEditController extends Controller
{

    protected function validator(array $data, $type)
    {
        $messages = [
          'required' => ':attribute この値は必須です。',
          'phone.regex'    => ':attribute 値の形式が正しくありません。',
        ];

        return Validator::make($data, [
            'company_name'           => 'required|string|max:50',
            'phone'                  => 'required|regex:/^([0-9\s\-]*)$/|max:20|min:5',
            'address'                => 'required',
            'company_roles_id'       => 'required',
        ], $messages);
    }

    public function index($id)
    {
        $data['item']           = Company::with('company_roles')->where('companies.id', $id)->first();
        $data['item']->company_role_options = CompanyRole::pluck('label', 'id');

        $data['page_title']     = __('label.corporate_detail');
        $data['form_action']    = route('admin.company.edit.store', $id);
        $data['page_type']      = 'edit';

        return view('backend.company.edit.index', $data);
    }

    public function store(Request $request, $id)
    {
        $data               = $request->all();
        $currentCompany     = Company::find($id);
        $this->validator($data, 'update')->validate();
        $currentCompany->update($data);
        return redirect()->route('admin.company.edit', $id)->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
    }
}
