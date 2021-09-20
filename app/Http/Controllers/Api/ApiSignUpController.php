<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CitiesAreas;
use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Prefecture;
use App\Models\Town;

class ApiSignUpController extends Controller
{
    public function getLocation(Request $request)
    {   
        $data = $request;        

        $response['prefecture'] = Prefecture::find($data['prefecture_id']);
        $response['city']       = City::find($data['city_id']);
        $response['cities_area']       = CitiesAreas::find($data['town_id']);        

        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
}
