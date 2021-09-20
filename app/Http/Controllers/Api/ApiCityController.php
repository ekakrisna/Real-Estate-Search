<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CitiesAreas;
use App\Models\GroupLine;
use Illuminate\Http\Request;

class ApiCityController extends Controller
{
    public function cityAreaList(Request $request){
        if( !empty( $request->city )){
            $id = $request->city;
            // $response = CitiesAreas::where( 'cities_id', $request->city )->get();
            $response = GroupLine::with(['cities_areas' => function($q) use($id) {                
                $q->where('cities_id', '=', $id)->orderBy('display_name_kana')->orderBy('group_line_id');
            }])->orderBy('id')->get();
            return response()->json( $response, 200, [], JSON_NUMERIC_CHECK );
        }
    }
}
