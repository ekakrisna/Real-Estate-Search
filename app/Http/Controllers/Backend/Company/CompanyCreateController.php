<?php

namespace App\Http\Controllers\Backend\Company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyRole;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CompanyCreateController extends Controller
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

    public function index()
    {
        $data['item']               = new Company();        
        $data['item']->company_role_options = CompanyRole::pluck('label', 'id');
        $data['page_title'] = __('label.create_new_corporate');
        $data['form_action'] = route('admin.company.create.store');
        $data['page_type']  = 'create';        
        return view('backend.company.create.index', $data);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $this->validator($data, 'create')->validate();

        $data['post_code'] = 0;
        $data['is_active'] = 1;
        // Store Company
        $new = new Company();
        $new->fill($data)->save();

        return redirect()->route('admin.company.list')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
    }
}
