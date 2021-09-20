<?php

namespace App\Http\Controllers\Backend\Company;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\CompanyRole;
use App\Models\CompanyUserTeam;
use App\Traits\LogActivityTrait;
use Illuminate\Http\Request;
use Redirect;

class CompanyController extends Controller
{
    use LogActivityTrait;    

    public function index()
    {
        $data = new \stdClass;
        $data->page_title = __('label.list_of_corp');
        $data->Company = Company::with('company_roles')->get(); 
        $data->CompanyRole = CompanyRole::all();
        // dd($data);
        return view('backend.company.index.index', (array) $data);
    }

    public function filter(Request $request)
    {        
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');        
        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];        
        // Base query        
        $relations = collect(['company_roles']);
        $query = Company::select('*');                 
        // View perpage        
        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }
                
        if (!empty($filter->datestart)) {
            $query->whereDate('created_at', '>=', $filter->datestart);
        }
        if (!empty($filter->datefinish)) {
            $query->whereDate('created_at', '<=', $filter->datefinish);
        }
        // ------------------------------------------------------------------
        // Minimum user ID
        // ------------------------------------------------------------------
        if (!empty($filter->min)) {
            $query->where('id', '>=', (int) $filter->min);
        }                
        // Maximum user ID        
        if (!empty($filter->max)) {
            $query->where('id', '<=', (int) $filter->max);
        }            
        // User role filter        
        if (!empty($filter->role)) {
            $roleID = (int) $filter->role;
            $query->whereHas('company_roles', function (Builder $sale) use ($roleID) {
                $sale->where('id', $roleID);
            });
        }        
        
        if (!empty($filter->status)) {
            if($filter->status == '2') {
                $query->where( 'is_active', 0 );
            } else {
                $query->where( 'is_active', (int) $filter->status );
            }
        }    

        // General search        
        if (!empty($filter->search)) {            
            $query->where(function ($query) use ($filter) {                
                $keywords = preg_replace('/\s+/', ' ', $filter->search);
                $keywords = explode(' ', trim($keywords));                                
                // Search company name                
                $query->where(function ($query) use ($keywords) {
                    if (!empty($keywords)) foreach ($keywords as $keyword) {
                        $query->orWhere('company_name', 'LIKE', "%{$keyword}%");                        
                    }
                });                                     
            });
        }        
        
        // Result order        
        $orders = [ 'id', 'company_name', 'label', 'created_at', 'is_active' ];//'user_count'
        // $orders = [ 'id', 'name', 'email', 'status', 'role', 'company' ];
        if( !empty( $filter->order ) && in_array( $filter->order, $orders )){            
            $order = null;
            $direction = $filter->direction ?? 'asc';
            if( 'is_active' == $filter->order ) $order = 'is_active';
            else $order = $filter->order; 
            $relationBasedOrders = [ 'user_count', 'label'];
            if( in_array( $filter->order, $relationBasedOrders )){                
                if( 'label' === $filter->order ){
                    $query->orderBy( 
                        CompanyRole::select( 'label' )
                            ->whereColumn( 'company_roles.id', 'companies.company_roles_id' ),
                        $direction
                    );
                }                                            
            }            
            else if( $order ) $query = $query->orderBy( $order, $direction );            
        }        

        
        // Add the relations, make sure they are unique                     
        $query->with($relations->unique()->all())->withCount('company_users');  
        // Paginate the result        
        $paginator = $query->paginate($perpage, $columns, 'page', $page);        
        
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);        
    }

    public function destroy($id)
    {
        $item = Company::findOrFail($id);
        $item->delete();
        //$this->saveLog('Delete Company', 'Delete Company, Name : ' . $item->company_name . '', Auth::user()->id);
        return 1;
    }

}
