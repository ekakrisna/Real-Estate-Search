<?php

namespace App\Http\Controllers\Backend\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerContactUs;
use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Database\Eloquent\Builder;

class CustomerContactHistoryController extends Controller
{
    //===================================================
    //B11 ALL USER CONTACT HISTORY TABLE LIKE
    //===================================================
    public function index()
    {
        $data = new \stdClass;
        $data->page_title = __('label.contact_contact_history');
        $data->CustomerContactUs = CustomerContactUs::with('customer.company_user.company')->get();
        $data->Coroprate = Company::all();
        $data->Customer = CompanyUser::all();
        return view('backend.customer.contact_histories.index', (array) $data);
    }

    public function flag( $id ){
        $response = new \stdClass;
        $response->status = 'success';

        $inquiry = CustomerContactUs::find( $id );
        if( !$inquiry ) $response->status = 'error';
        else {
            $inquiry->flag = !$inquiry->flag;
            $inquiry->save();
            $response->result = $inquiry->flag;
        }

        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
    }

    public function filter(Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');        
        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];        
        $relations = ['customer.company_user.company'];
        $query = CustomerContactUs::select('*');
                    
        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }
        // Active status filter
        if (!empty($filter->status)) {
            if($filter->status == '2') {
                $query->where( 'is_finish', 0 );
            } else {
                $query->where( 'is_finish', (int) $filter->status );
            }
        }            
        // Minimum - Max date filter        
        if (!empty($filter->datestart)) {
            $query->whereDate('created_at', '>=', $filter->datestart);
        }
        if (!empty($filter->datefinish)) {
            $query->whereDate('created_at', '<=', $filter->datefinish);
        }
        // General search        
        if (!empty($filter->search)) {
            $keywords = preg_replace('/\s+/', ' ', $filter->search);
            $keywords = explode(' ', trim($keywords));
            $query->where(function ($query) use ($filter) {
                $keywords = preg_replace('/\s+/', ' ', $filter->search);
                $keywords = explode(' ', trim($keywords));
                // Search user                
                $query->where(function ($query) use ($keywords) {
                    if (!empty($keywords)) foreach ($keywords as $keyword) {
                        $query->whereHas('customer', function ($query) use ($keyword) {
                            $query->where('name', 'LIKE', "%{$keyword}%");
                        });
                    }
                });
            });
        }

        // Corporate in charge
        if (!empty($filter->corporate)) {
            $corporateID = (int) $filter->corporate;
            $query->whereHas('customer.company_user.company', function (Builder $sale) use ($corporateID) {
                $sale->where('id', $corporateID);
            });
        }

        $orders = [ 'flag_favorite', 'id', 'created_at', 'is_finish','flag', 'name','company_users_id', 'company'];        
        if( !empty( $filter->order ) && in_array( $filter->order, $orders )){            
            $order = null;
            $direction = $filter->direction ?? 'asc';                                
            $order = $filter->order;           
            if($order == 'name'){
                $query = $query->with('customer')
                                ->orderBy('name', $direction);
            } elseif($order == 'flag_favorite'){
                $query = $query->orderBy( 
                    Customer::select( 'flag' )
                        ->whereColumn( 'customer_contact_us.customers_id', 'customers.id' ),
                    $direction
                );                
            } elseif($order == 'company_users_id'){
                $query = $query->with('customer.company_user.company')
                                ->orderBy('company_name', $direction);
            } elseif($order == 'company'){
                $query = $query->with('customer.company_user')
                                ->orderBy('name', $direction);
            } elseif( $order ) {
                $query = $query->orderBy( $order, $direction );
            }            
        }        

        // Person in charge
        if (!empty($filter->person)) {
            $personID = (int) $filter->person;
            $query->whereHas('customer.company_user', function (Builder $sale) use ($personID) {
                $sale->where('id', $personID);
            });
        }
        // Add the relations, make sure they are unique
        $query->with($relations);        
        // Paginate the result
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }



    //===================================================
    //B12 USER CONTACT HISTORY SPESIFIC USER TABLE LIKE
    //===================================================
    public function contact_history($id)
    {
        $data['page_title']     = __('label.contact_contact_history');
        $data['id']             = $id;
        $data['page_type']      = 'detail';

        $data['customer_detail']= Customer::where('id', $id)->with(['company_user', 'company_user.company'])->first();

        return view('backend.customer.contact_history.index', $data);
    }

    public function filter_contact($id, Request $request)
    {
        $filter = (object) $request->filter;
        $response = (object) array('status' => 'success');

        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];

        // ------------------------------------------------------------------
        // Base query
        // ------------------------------------------------------------------
        //$relations = collect([ 'role', 'company.role' ]);
    
        $relations = collect(['contact_us_type']);
        $query = CustomerContactUs::select('*')->where('customers_id', $id);
        $query->with($relations->unique()->all());

        // ------------------------------------------------------------------

        $list = [1, 2, 5, 10, 20, 50];
        if (!empty($filter->perpage)) {
            $view = (int) $filter->perpage;
            if (in_array($view, $list)) $perpage = $view;
        }

        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = ['id', 'created_at', 'is_finish', 'properties_id'];
        if (!empty($filter->order) && in_array($filter->order, $orders)) {
            // --------------------------------------------------------------
            $order = null;
            $direction = $filter->direction ?? 'asc';
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Order by 
            // --------------------------------------------------------------
           // if ('status' == $filter->order) $order = 'is_active';
            //else 
            $order = $filter->order; // Everything else
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            if ($order) $query = $query->orderBy($order, $direction);
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Add the relations, make sure they are unique
        // ------------------------------------------------------------------
        // $query->with( $relations->unique()->all());
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Paginate the result
        // ------------------------------------------------------------------
        $paginator = $query->paginate($perpage, $columns, 'page', $page);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $response->result = $paginator;
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    //===================================================
    //B12 USER CONTACT HISTORY TABLE LIKE
    //===================================================
}
