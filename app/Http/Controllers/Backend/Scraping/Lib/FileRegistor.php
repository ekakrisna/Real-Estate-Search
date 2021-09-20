<?php

namespace App\Http\Controllers\Backend\Scraping\Lib;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * This class only use scraping file upload screen.
 */
class FileRegistor
{

    const notEnable = 0;
    const isFileExists = 1;

    private $dayOfWeeks = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    /**
     * Check Scraping file exist and contents.
     *
     * Return the following string
     * - 'notEnable'
     * - 'isFileExists'
     * - '' : OK! Not uploaded yet, you can upload it!
     */
    public function checkScrapingFile(Request $request, $directoryPath, $uploadHistoryOrm, $headerDef)
    {
        $response = new \stdClass;
        $file = $request->file('file');
        $filename = $request->file('file')->getClientOriginalName();
        $filepath = 'files/' . $directoryPath . '/' . $filename;
        $isFileExists = Storage::exists($filepath);
        //$isFileExists = false;
        $isFileNameExists = $uploadHistoryOrm::where('file_name', '=', $filename)->exists();
        //$isFileNameExists = false;

        // --------------------------------------------------------
        // Start to read contents
        // --------------------------------------------------------
        $lineArray = $this->getRowArrayFromUploadedFile($file);
        $file = null;
        if (count($lineArray) < 0) {
            return 'Error:noLine';
        }

        if (!$this->checkCsvHeader($lineArray[0], $headerDef)) {
            return  'Error:invalidHeader';
        }

        $dayOfWeek = $this->getDayOfWeekFromFileName($filename);

        if ($dayOfWeek == null) {
            return  'Error:dayOfWeekIsNotFound';
        }

        if ($isFileExists || $isFileNameExists) {
            return 'Error:isFileAlreadyExists';
        }
        // Checking is all OK.
        return "";
    }

    /**
     * store data to database using upload file.
     *
     * @param Request $request
     * @param [type] $uploadHistoryOrm = ORM for each uplad history.
     * @param [type] $dataRegisterClass = This class is register to database using dataRow class.
     * @param [type] $dataRowClass = This class is register to database using dataRow class.
     * @return void
     */
    public function store(Request $request, $directoryPath, $uploadHistoryOrm, $dataRegisterClass, $dataRowClass)
    {
        try {
            $response = new \stdClass;
            $file = $request->file('file');
            $filename = $request->file('file')->getClientOriginalName();
            $filepath = 'files/' . $directoryPath . '/' . $filename;
            $lineArray = $this->getRowArrayFromUploadedFile($request->file('file'));

            // Store record of uploadHistory of DB.
            $scraping = new $uploadHistoryOrm();
            $scraping->file_name = $filename;
            $scraping->created_at = Carbon::now();
            $store = $scraping->save();

            // --------------------------------------------------------
            // Start to read contents
            // --------------------------------------------------------
            $dayOfWeek = $this->getDayOfWeekFromFileName($filename);
            if ($store) {
                $path = Storage::put($filepath, file_get_contents($file));
                if ($path) {
                    $response->data = $uploadHistoryOrm::orderBy('created_at', 'DESC')->get();
                }

                $i = 0;

                $ScrapingDataRegister = new $dataRegisterClass();

                foreach ($lineArray as $line) {
                    // header check
                    if ($i == 0) {
                        $i++;
                        continue;
                    }

                    if ($i != 0) {
                        $reinse = new $dataRowClass();
                        $result = $reinse->convertFromScrapingData($line);
                        if (!$result) {
                            sendMessageViaChatworkScrapingReport("REINS", \config('chatwork.message_site-layout-is-changed'));
                            continue;
                        }

                        $ScrapingDataRegister->addData($reinse);
                    }
                }

                $result = $ScrapingDataRegister->dataRegister($dayOfWeek);

                // send message via chat work.
                if (!$result) {
                    if (!app()->isLocal()) {
                        sendMessageViaChatworkScrapingReport("REINS", \config('chatwork.message_site-dataformat-is-changed'));
                    }
                    return;
                }
            }
        } catch (\Throwable $e) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            sendMessageOfErrorReport("Backend/FileRegistor (store Function), Error: ", $e);
            //------------------------------------------------------

            throw $e;
        }
        return $response;
    }

    private function getRowArrayFromUploadedFile($argFile)
    {
        $file = new \SplFileObject($argFile);
        $file->setFlags(
            \SplFileObject::READ_CSV |
                \SplFileObject::READ_AHEAD |
                \SplFileObject::SKIP_EMPTY |
                \SplFileObject::DROP_NEW_LINE
        );
        $file->setCsvControl(" ");

        $lineArray = [];
        foreach ($file as $line) {
            $lineArray[] = $line;
        }
        return $lineArray;
    }

    private function checkCsvHeader($headerRow, $headerDef)
    {
        if (count($headerRow) != count($headerDef)) {
            return false;
        }

        for ($i = 0; $i < count($headerDef); $i++) {
            $index = $i;
            $header = $headerRow[$index];
            $def = $headerDef[$index];
            if (strpos($header, $def) >= 0) {
                continue;
            }
            return false;
        }
        return true;
    }

    private function getDayOfWeekFromFileName($fileName)
    {
        for ($i = 0; $i < count($this->dayOfWeeks); $i++) {
            $result = strpos($fileName, $this->dayOfWeeks[$i]);
            if ($result) {
                return $this->dayOfWeeks[$i];
            }
        }
        return null;
    }
}
