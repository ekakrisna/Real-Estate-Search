<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Customer as Cust;

class ApiCustomerController extends Controller
{
    public function customerEmailExists( Request $request ){
        if( empty( $request->email )) return false;

        $email = $request->email;
        $userID = $request->user ?? null;
        
        $query = Cust::where( 'email', $email );
        if( $userID ){
            $user = Cust::find( $userID );
            if( $user ) $query->where( 'email', '<>', $user->email );
        }

        return response()->json( !!$query->count() );
    }

    public function customerEmailCheck( Request $request ){
        if( empty( $request->email )) return false;

        $email = $request->email;
        $userID = $request->user ?? null;
        
        $check = 1;
        if($userID=="" || $userID==Null){
            $query    = Cust::where('email',$email)->first();
            if ($query) { 
                $check = 0;
            }
        }else{
            $query = Cust::where( 'id', $userID )->first();
            if( $email==$query->email ){
                $check = 0;
            }
        }
        
        return response()->json( !!$check );
    }

    public function customerEmailCheckPassword( Request $request ){        
        if( empty( $request->email )) return false;

        $email = $request->email;
        $userID = $request->user ?? null;
        
        $check = 1;
        if($userID=="" || $userID==Null){
            $query    = Cust::where('email',$email)->first();
            if ($query) { 
                $check = 0;
            }
        }else{
            $query = Cust::where('email',$email)->first();        
            if( $email==$query->email ){
                $check = 0;
            }
        }
        
        return response()->json( !!$check );
    }
}
