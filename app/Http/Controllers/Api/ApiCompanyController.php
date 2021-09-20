<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use Illuminate\Http\Request;
// --------------------------------------------------------------------------
use App\Models\Company;
use App\Models\CompanyUser as User;
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
class ApiCompanyController extends Controller {
    // ----------------------------------------------------------------------
    // Return all companies
    // ----------------------------------------------------------------------
    public function all(){
        $response = Company::orderBy('company_name', 'ASC')->get();
        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Return single company
    // ----------------------------------------------------------------------
    public function single( Request $request ){        
        if( !empty( $request->id )){
            $response = Company::find( $request->id );
            return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        }
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Return all in-charge person (user) under a specific company
    // ----------------------------------------------------------------------
    public function persons( Request $request ){               
        if( !empty( $request->id )){
            $response = User::where( 'companies_id', $request->id )->get();
            return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        }
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Return all users under the company
    // Accepts data in format of { company: companyID }
    // ----------------------------------------------------------------------
    public function users( Request $request ){        
        if( !empty( $request->company )){
            $response = User::where( 'companies_id', $request->company )->get();
            return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        }
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
