<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\DatatablesHelper;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use App\Helpers\Select2AjaxHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    public function __construct(){
    }

    protected function validator( array $data, $type ){
        return Validator::make($data, [
            'title'         => 'required|string|max:100',
            'company_id'    => 'exists:companies,id',
            'body'          => 'required|string|max:255',
            'image'         => 'image|mimes:jpeg,bmp,png,jpg|max:1024',
            'pdf_file'      => 'file|mimes:pdf|max:20000',
            'publish_date'  => 'date',
            'status'        => 'required|in:draft,publish',
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
    public function show( $param ){
        switch ($param){
            case 'json':
                $model = News::query();
                return (new DatatablesHelper)->instance($model)
                ->addColumn('custom_status', function($query){
                    if ($query->status == 'draft') {
                        return 'Drafted';
                    }else {
                        return 'Published';
                    }
                })
                ->toJson();
            case 'searchcompany':
                return Select2AjaxHelper::set(Company::query(), 'id', 'company_name');
                break;
        }
        abort(404);
    }

    public function index(){
        $data['page_title'] = __('label.news');
        $data['filter_select_columns'] = [
            'status' => [
                'draft' => 'draft',
                'publish' => 'publish',
            ],
            // example custom filter
            'custom_status' => [
                'draft' => 'Drafted',
                'publish' => 'Published',
            ],
        ];

        return view('backend.news.index', $data);
    }

    public function create(){
        $data['item']       = new News();
        $data['page_title'] = __('label.add') . ' ' . __('label.news');
        $data['form_action']= route('admin.news.store');
        $data['page_type']  = 'create';

        return view('backend.news.form', $data);
    }

    public function store(Request $request){
        $data = $request->all();
        $this->validator($data, 'create')->validate();

        $data['image']     = ImageHelper::upload( $request->file('image') );
        $data['pdf_file']  = FileHelper::upload( $request->file('pdf_file') );
        $data['admin_id']  = Auth::user()->id;

        $new = new News();
        $new->fill($data)->save();
        return redirect()->route('admin.news.index')->with('success', __('label.SUCCESS_CREATE_MESSAGE'));
    }

    public function edit($id){
        $data['item']           = News::find($id);

        $data['page_title']     = __('label.edit') . ' ' . __('label.news');
        $data['form_action']    = route('admin.news.update', $id);
        $data['page_type']      = 'edit';

        return view('backend.news.form', $data);
    }

    public function update(Request $request, $id){
        $data               = $request->all();
        $this->validator($data, 'update')->validate();

        $edit = News::find($id);

        $data['image']     = ImageHelper::update( $request->file('image'), $edit->image, $data['removable_image']['image'] ); // $data['removable_image']['image'] -> ['image'] is field name
        $data['pdf_file']  = FileHelper::update( $request->file('pdf_file'), $edit->pdf_file ); /** @todo : removing file for non required fields like image helper have **/
        $data['admin_id']  = Auth::user()->id;

        $edit->update($data);

        return redirect()->route('admin.news.edit', $id)->with('success', __('label.SUCCESS_UPDATE_MESSAGE'));
    }

    public function destroy($id){
        $delete = News::findOrFail($id);
        ImageHelper::removeImage($delete->image);
        $delete->delete();

        $this->saveLog('Delete News', 'Delete News, Title : ' . $delete->title . '', Auth::user()->id);

        return 1;
    }
}
