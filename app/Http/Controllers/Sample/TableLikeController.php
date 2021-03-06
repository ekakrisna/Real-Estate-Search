<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Sample;
use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
// --------------------------------------------------------------------------
use App\Models\Company;
use App\Models\CompanyUser as User;
use App\Models\CompanyUserRole as Role;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
class TableLikeController extends Controller {
    // ----------------------------------------------------------------------
    public function index(){
        $data = new \stdClass;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Frontend data
        // ------------------------------------------------------------------
        $data->roles = Role::all();
        $data->company = Company::all();
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return view( 'backend.sample.tablelike.index', (array) $data );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Frontend filter
    // ----------------------------------------------------------------------
    public function filter( Request $request ){
        // ------------------------------------------------------------------
        $filter = (object) $request->filter;
        $response = (object) array( 'status' => 'success' );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Default configuration
        // ------------------------------------------------------------------
        $page = $filter->page ?? 1;
        $perpage = 10;
        $columns = ['*'];
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Base query
        // ------------------------------------------------------------------
        $relations = collect([ 'role', 'company.role' ]);
        $query = User::select('*');
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // View perpage
        // ------------------------------------------------------------------
        $list = [ 1, 2, 5, 10, 20, 50 ];
        if( !empty( $filter->perpage )){
            $view = (int) $filter->perpage;
            if( in_array( $view, $list )) $perpage = $view;
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Minimum user ID
        // ------------------------------------------------------------------
        if( !empty( $filter->min )){
            $query->where( 'id', '>=', (int) $filter->min );
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Maximum user ID
        // ------------------------------------------------------------------
        if( !empty( $filter->max )){
            $query->where('id', '<=', (int) $filter->max);
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // User role filter
        // ------------------------------------------------------------------
        if( !empty( $filter->role )){
            $roleID = (int) $filter->role;
            $query->whereHas( 'role', function( Builder $sale ) use( $roleID ){
                $sale->where( 'id', $roleID );
            });
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Company filter
        // ------------------------------------------------------------------
        if( !empty( $filter->company )){
            $companyID = (int) $filter->company;
            $query->whereHas( 'company', function( Builder $sale ) use( $companyID ){
                $sale->where( 'id', $companyID );
            });
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Active status filter
        // ------------------------------------------------------------------
        if( !empty( $filter->status )){
            $query->where( 'is_active', (int) $filter->status );
        }
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // General search
        // ------------------------------------------------------------------
        if( !empty( $filter->search )){
            // --------------------------------------------------------------
            $query->where( function( $query ) use( $filter ){
                // ----------------------------------------------------------
                $keywords = preg_replace( '/\s+/', ' ', $filter->search );
                $keywords = explode( ' ', trim( $keywords ));
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Search user
                // ----------------------------------------------------------
                $query->where( function( $query ) use( $keywords ){
                    if( !empty( $keywords )) foreach( $keywords as $keyword ){
                        $query->orWhere( 'name', 'LIKE', "%{$keyword}%" );
                        $query->orWhere( 'email', 'LIKE', "%{$keyword}%" );
                    }
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Search company
                // ----------------------------------------------------------
                $query->orWhereHas( 'company', function( Builder $query ) use( $keywords ){
                    $query->where( function( $query ) use( $keywords ){
                        if( !empty( $keywords )) foreach( $keywords as $keyword ){
                            $query->orWhere( 'company_name', 'LIKE', "%{$keyword}%" );
                        }
                    });
                });
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Search user role
                // ----------------------------------------------------------
                $query->orWhereHas( 'role', function( Builder $query ) use( $keywords ){
                    $query->where( function( $query ) use( $keywords ){
                        if( !empty( $keywords )) foreach( $keywords as $keyword ){
                            $query->orWhere( 'name', 'LIKE', "%{$keyword}%" );
                            $query->orWhere( 'label', 'LIKE', "%{$keyword}%" );
                        }
                    });
                });
                // ----------------------------------------------------------
            });
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Result order
        // ------------------------------------------------------------------
        $orders = [ 'id', 'name', 'email', 'status', 'role', 'company' ];
        if( !empty( $filter->order ) && in_array( $filter->order, $orders )){
            // --------------------------------------------------------------
            $order = null;
            $direction = $filter->direction ?? 'asc';
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Order by 
            // --------------------------------------------------------------
            if( 'status' == $filter->order ) $order = 'is_active';
            else $order = $filter->order; // Everything else
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Relation based order
            // --------------------------------------------------------------
            $relationBasedOrders = [ 'role', 'company' ];
            if( in_array( $filter->order, $relationBasedOrders )){
                // ----------------------------------------------------------
                // Order user by the company table
                // ----------------------------------------------------------
                if( 'company' === $filter->order ){
                    $query->orderBy( 
                        Company::select( 'company_name' )
                            ->whereColumn( 'companies.id', 'company_users.companies_id' ),
                        $direction
                    );
                }
                // ----------------------------------------------------------

                // ----------------------------------------------------------
                // Order user by the user-role table
                // ----------------------------------------------------------
                elseif( 'role' === $filter->order ){
                    $query->orderBy( 
                        Role::select( 'name' )
                            ->whereColumn( 'company_user_roles.id', 'company_users.company_user_roles_id' ),
                        $direction
                    );
                }
                // ----------------------------------------------------------
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Regular order
            // --------------------------------------------------------------
            else if( $order ) $query = $query->orderBy( $order, $direction );
            // --------------------------------------------------------------
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Start date filter
        // ------------------------------------------------------------------
        if( !empty( $filter->start )){
            $start = Carbon::parse( $filter->start );
            $query = $query->whereDate( 'created_at', '>=', $start );
        }
        // ------------------------------------------------------------------
        // End date filter
        // ------------------------------------------------------------------
        if( !empty( $filter->end )){
            $end = Carbon::parse( $filter->end );
            $query = $query->whereDate( 'created_at', '<=', $end );
        }
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Query debugging
        // ------------------------------------------------------------------
        // dd( $query->toSql(), $query->getBindings());
        // ------------------------------------------------------------------


        // ------------------------------------------------------------------
        // Add the relations, make sure they are unique
        // ------------------------------------------------------------------
        $query->with( $relations->unique()->all());
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Paginate the result
        // ------------------------------------------------------------------
        $paginator = $query->paginate( $perpage, $columns, 'page', $page );
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        $response->result = $paginator;
        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
