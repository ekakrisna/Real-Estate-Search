<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CitiesAreas;
use App\Models\City;
use App\Models\GroupLine;
use App\Models\Prefecture;
use App\Models\PrefectureArea;
use App\Models\Town;
use Illuminate\Http\Request;

class ApiLocationController extends Controller
{
    // ----------------------------------------------------------------------
    // Return all prefecture
    // ----------------------------------------------------------------------
    public function prefecture()
    {
        $response = Prefecture::where('id',Prefecture::Miyagi_id)->get();
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Return Prefecture Area
    // ----------------------------------------------------------------------
    public function prefecture_areas(Request $request)
    {
        if (!empty($request->id)) {
            $response = PrefectureArea::where('prefecture_id', $request->id)->get();
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Return Single Prefecture Area
    // ----------------------------------------------------------------------
    public function prefecture_area(Request $request)
    {
        if (!empty($request->id)) {
            $response = PrefectureArea::where('id', $request->id)->first();
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Return single city
    // ----------------------------------------------------------------------
    public function city(Request $request)
    {
        if (!empty($request->prefecture_id)) {            
            $response = null;

            $prefecture_id = $request->prefecture_id;
            $prefecture_area_id = $request->prefecture_areas_id;

            $prefectureAreaModel =  PrefectureArea::where('id', $prefecture_area_id)->first();
            if($prefectureAreaModel == null){
                return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
            }

            if($prefectureAreaModel->is_all_show){                
                $response = GroupLine::with(['cities' => function($q) use($prefecture_id) {                
                    $q->where('prefectures_id', '=', $prefecture_id)->orderBy('name_kana')->orderBy('group_line_id');
                }])->orderBy('id')->get();

            }else{                
                $response = GroupLine::with(['cities' => function($q) use($prefecture_id, $prefecture_area_id) {                
                    $q->where('prefectures_id', '=', $prefecture_id)->where('prefecture_area_id',"=",$prefecture_area_id)->orderBy('name_kana')->orderBy('group_line_id');
                }])->orderBy('id')->get();
            }
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }

    // ----------------------------------------------------------------------
    // Return town
    // ----------------------------------------------------------------------
    public function town(Request $request)
    {
        if (!empty($request->id)) {
            $response = Town::where('cities_id', $request->id)->orderBy('name','desc')->get();
            
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Return Cities Area
    // ----------------------------------------------------------------------
    public function cities_areas(Request $request)
    {
        if (!empty($request->id)) {
            $id = $request->id;            
            $response = GroupLine::with(['cities_areas' => function($q) use($id) {                
                $q->where('cities_id', '=', $id)->orderBy('display_name_kana')->orderBy('group_line_id');
            }])->orderBy('id')->get();
            
            return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
        }
    }
    // ----------------------------------------------------------------------
}
