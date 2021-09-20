<?php

namespace App\Http\Controllers\Backend\Scraping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Backend\Scraping\Lib\FileRegistor;
use App\Models\LpScrapingFileUpload;
use App\Console\Scraping\DataRow\LpReinsDataRow;
use App\Console\Scraping\DataRegister\LpScrapingDataRegister;
use Illuminate\Support\Facades\Log;

class LpScrapingController extends Controller
{
    const DIRECTORY_PATH = 'uploaded_csv/SalesService';
    public function index()
    {
        $data['page_title'] = __('label.scraping_upload_data');
        $data['scraping'] = LpScrapingFileUpload::orderBy('created_at', 'DESC')->get();
        return view('backend.lp.scraping.index', $data);
    }

    public function uploadFile(Request $request)
    {
        $fileRegistorClass = new FileRegistor();
        $headerDef = [
            "price",
            "landarea",
            "location",
            "trafic",
            "property_no", // "property_no" is abolished!! please refer "*_publish.property_number"
            "building_area",
            "house_layout",
            "connecting_road",
            "contracted_years",
            "building_age",
            "property_category",
        ];
        $response = new \stdClass;
        $result = $fileRegistorClass->checkScrapingFile(
            $request,
            self::DIRECTORY_PATH,
            LpScrapingFileUpload::class,
            $headerDef
        );
        if ($result != "") {
            $response->status = $result;
            return response()->json($response, 406, [], JSON_NUMERIC_CHECK);
        }

        try {
            $response = $fileRegistorClass->store(
                $request,
                self::DIRECTORY_PATH,
                LpScrapingFileUpload::class,
                LpScrapingDataRegister::class,
                LpReinsDataRow::class
            );
        } catch (Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Backend/LpScrapingController (uploadFile Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Backend/LpScrapingController (uploadFile Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = "serverError";
            return response()->json($response, 500, [], JSON_NUMERIC_CHECK);
        }

        $response->status = 'success';
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
}
