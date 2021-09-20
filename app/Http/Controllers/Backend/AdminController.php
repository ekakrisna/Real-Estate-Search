<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\DatatablesHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\LogActivityTrait;
use DataTables;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    use LogActivityTrait;

    public function __construct()
    {
    }

    protected function validator(array $data, $type)
    {
        return Validator::make($data, [
            'display_name'  => 'required|string|max:100',
            'email'         => 'required|email|max:255|unique:admins,email' . ($type == 'update' ? ',' . $data['id'] : ''),
            'password'      => $type == 'create' ? 'required|string|min:8|max:255' : 'string|min:8|max:255',
        ]);
    }

    /**
     * @param string parameter - url of custom resources page
     *
     * @return string - Any
     *
     * [AJax from Data Table : Defined "DataTable(..." method on backend/_base/content_datatables.blade.php ]
     *
     * You may add custom page/api/route, this wrapped middleware as well
     */
    public function show($param)
    {
        if ($param == 'json') {

            $model = admin::where('admin_role_id', AdminRole::ROLE_GENERAL_ADMIN);
            return (new DatatablesHelper)->instance($model)->toJson();
        }
        abort(404);
    }

    public function index()
    {
        $data['page_title'] = __('label.admin');
        return view('backend.admin.index', $data);
    }

    public function create()
    {
        $data['item']       = new Admin();
        $data['page_title'] = __('label.add') . ' ' . __('label.admin');
        $data['form_action'] = route('admin.admins.store');
        $data['page_type']  = 'create';

        return view('backend.admin.form', $data);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $this->validator($data, 'create')->validate();
            $data['admin_role_id']  = AdminRole::ROLE_GENERAL_ADMIN;
            $data['password']       = bcrypt($data['password']);
            $new = new Admin();
            $new->fill($data)->save();
            return redirect()->route('admin.admins.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
        } catch (\Exception $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Backend/AdminController (store Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Backend/AdminController (store Function), Error: ", $e);
            //------------------------------------------------------

            throw new \App\Exceptions\ErrorLogs($e);
        }
    }

    public function edit($id)
    {
        $data['item']           = Admin::find($id);

        $data['page_title']     = __('label.edit') . ' ' . __('label.admin');
        $data['form_action']    = route('admin.admins.update', $id);
        $data['page_type']      = 'edit';

        return view('backend.admin.form', $data);
    }

    public function update(Request $request, $id)
    {
        $data               = $request->all();
        $currentAdmin       = Admin::find($id);
        $data['password']   = !empty($data['password']) ? $data['password'] : $currentAdmin['password'];
        $data['id']         = $id;
        $this->validator($data, 'update')->validate();

        if (Hash::needsRehash($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $currentAdmin->update($data);

        return redirect()->route('admin.admins.edit', $id)->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
    }

    public function destroy($id)
    {
        if (Auth::user()->id != $id) {
            $item = Admin::findOrFail($id);
            $item->delete();

            $admin_email    = $item->email;
            $this->saveLog('Delete Admin', 'Delete Admin, Email : ' . $admin_email . '', Auth::user()->id);

            return 1;
        }
    }
}
