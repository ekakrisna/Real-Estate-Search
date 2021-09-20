<?php
// --------------------------------------------------------------------------
namespace App\Http\Controllers\Api;
// --------------------------------------------------------------------------
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// --------------------------------------------------------------------------
use App\Models\Customer;
use App\Models\CompanyUser as User;
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
class ApiUserController extends Controller {
    // ----------------------------------------------------------------------
    // Check if user email exists
    // ----------------------------------------------------------------------
    public function emailExists( Request $request ){
        if( empty( $request->email )) return response()->json( null );

        $email = $request->email;
        $userID = (int) $request->user ?? null;
        
        $query = User::where( 'email', $email );
        if( $userID ){
            $user = User::find( $userID );
            if( $user ) $query->where( 'email', '<>', $user->email );
        }

        return response()->json( !!$query->count() );
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // All customers under the company-user
    // Accepts data in format of { user: userID }
    // ----------------------------------------------------------------------
    public function customers( Request $request ){
        if( empty( $request->user )) return response()->json([]);

        $userID = (int) $request->user;
        $response = Customer::where( 'company_users_id', $userID )->get();

        return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
    }
    // ----------------------------------------------------------------------
    
}
