<?php

namespace App\Http\Controllers\Backend\Scraping;

use App\Http\Controllers\Controller;
use App\Models\ScrapingFileUploadHistories;
use Illuminate\Http\Request;
use App\Console\Scraping\DataRow\ReinsDataRow;
use App\Console\Scraping\DataRegister\ScrapingDataRegister;
use App\Http\Controllers\Backend\Scraping\Lib\FileRegistor;
use Illuminate\Support\Facades\Log;

class ScrapingController extends Controller
{
    const DIRECTORY_PATH = 'uploaded_csv/TochiSearch';
    public function index()
    {
        $data['page_title'] = __('label.scraping_upload');
        $data['scraping'] = ScrapingFileUploadHistories::orderBy('created_at', 'DESC')->get();
        return view('backend.scraping.index', $data);
    }

    public function uploadFile(Request $request)
    {
        $fileRegistorClass = new FileRegistor();
        $headerDef = [
            "price",
            "landarea",
            "location",
            "trafic",
            "property_no"
        ];

        $response = new \stdClass;
        $result = $fileRegistorClass->checkScrapingFile(
            $request,
            self::DIRECTORY_PATH,
            ScrapingFileUploadHistories::class,
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
                ScrapingFileUploadHistories::class,
                ScrapingDataRegister::class,
                ReinsDataRow::class
            );
        } catch (Exception $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Backend/ScrapingController (uploadFile Function), Error: " . $e->getMessage());
            sendMessageOfErrorReport("Backend/ScrapingController (uploadFile Function), Error: ", $e);
            //------------------------------------------------------

            $response->status = "serverError";
            return response()->json($response, 500, [], JSON_NUMERIC_CHECK);
        }

        $response->status = 'success';
        return response()->json($response, 200, [], JSON_NUMERIC_CHECK);
    }
}
