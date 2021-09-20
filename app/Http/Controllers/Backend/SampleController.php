<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\DatatablesHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\LogActivity;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SampleController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @param string parameter - url of custom resources page
     *
     * @return string - Any
     *
     * *** It is sample pages ***
     * Please add some guide to easy to understand for future allys!
     * 今後の仲間たちのためにも分かりやすいサンプルを追加していきましょう！
     *
     * [AJax from Data Table : Defined "DataTable(..." method on backend/_base/content_datatables.blade.php ]
     * You may add custom page/api/route, this wrapped middleware as well
     */
    public function show(Request $request, $param)
    {
        if ($param == 'json') {
            $model = LogActivity::with('admin');

            // Exclude filter conditions
            if ($request->has('columns')) {
                $columns = $request->get('columns');
                foreach ($columns as $column) {
                    // [hint] Please implement here if you need custom filter by each filter.
                    if ($column['data'] == 'formatted_access_time') {
                        if (!empty($column['search']['value'])) {
                            //Ex: 2020-09/11 00:09 - 2020/10/06 23:10
                            $filter_access_time = explode(' - ', $column['search']['value']);
                            // Log::debug($filter_access_time);
                            $model = $model->where('access_time', '>=', $filter_access_time[0])
                                ->where('access_time', '<=', $filter_access_time[1]);
                        }
                    }
                }
            }

            \DB::enableQueryLog();
            $result = (new DatatablesHelper)->instance($model, false, false)
                // example custom filter
                ->addColumn('formatted_access_time', function ($query) {
                    return date('Y/m/d h:i', strtotime($query->access_time));
                })
                ->filterColumn('admin.display_name', function ($query, $keyword) {
                    $query->whereHas('admin', function ($q) use ($keyword) {
                        $q->where('display_name', 'like', '%' . $keyword . '%');
                    });
                })
                ->filterColumn('admin.email', function ($query, $keyword) {
                    $query->whereHas('admin', function ($q) use ($keyword) {
                        $q->where('email', 'like', '%' . $keyword . '%');
                    });
                })
                ->toJson();

            \Log::debug(\DB::getQueryLog());
            \DB::disableQueryLog();
            return $result;
        }
        abort(404);
    }

    public function index()
    {
        $data['page_title'] = __('label.implementation_sample');
        $data['filter_select_columns'] = [
            'user_roles' => UserRole::pluck('label', 'label')
        ];

        /* Necessary items for "Form items" sample. */
        $data['item']       = new Company();
        $data['item']->admin = new Admin();
        $data['form_action'] = route('admin.company.store');
        $data['page_type']  = 'create';

        return view('backend.sample.index', $data);
    }
}
