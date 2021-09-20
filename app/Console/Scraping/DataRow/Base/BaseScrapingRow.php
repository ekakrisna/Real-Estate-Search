<?php

namespace App\Console\Scraping\DataRow\Base;

use App\Models\Property;
use App\Models\Prefecture;
use App\Models\City;
use App\Models\Town;
use App\Console\Scraping\DataRow\ResultClass\ResultConvartRange;
use App\Console\Scraping\DataRow\ResultClass\AfterSplitRawData;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


abstract class BaseScrapingRow
{
    public $location = null;
    public $location_original = null;
    public $town_id = null;

    public $validate_location = true;

    public $minimum_price = null;
    public $maximum_price = null;
    public $validate_price = true;

    public $minimum_land_area = null;
    public $maximum_land_area = null;
    public $validate_land_area = true;

    public $land_status = "";
    public $traffic = null;

    public $url = "";
    public $publication_destination = "";
    public $property_number = null;
    /**
     *  Create this object from each data(API or CSV)
     *
     *
     * @param [type] $rowdata is row data getting by scraping.
     *                this data of type is changed by API or CSV.
     *                If API, type is Class.
     *                If CSV, type is Array.
     *                those difficlut is absorptioed
     * @return boolean
     */
    public function convertFromScrapingData($rowdata): bool
    {

        // get AfterSplitRawData class from rowdata.
        $afterSplitRawData = $this->getAfterSplitRawData($rowdata);

        // if split is wrong, return as false.
        if (!$afterSplitRawData->isCorrectlyGet) {
            return false;
        }

        // using result of split, convart to each property t his class.
        $this->convartAllValue($afterSplitRawData);
        return true;
    }

    private function convartAllValue($afterSplitRawData)
    {
        // convart location
        $afterConvartlocation  = $this->convartLocation($afterSplitRawData->location);

        $this->location_original = $afterConvartlocation['ORIGINAL_NAME'];
        $this->location = $afterConvartlocation['AFTER_CONVERT_FULL_NAME'];
        //$this->pre_location =  $afterConvartlocation['PRE_NAME'];

        //$this->is_match_location_serach =  $afterConvartlocation['IS_NAME_MATCH'];
        //$this->is_match_full_location = $afterConvartlocation['IS_FULL_NAME_MATCH'];

        $this->town_id = $afterConvartlocation['TOWN_ID'];
        $this->validate_location = $afterConvartlocation['TOWN_ID'] == null ? false : true;

        // convart LandArea
        $afterConvartLandArea  = $this->convartLocationArea($afterSplitRawData->landArea);
        $this->minimum_land_area = $afterConvartLandArea->afterConvartMinValue;
        $this->maximum_land_area = $afterConvartLandArea->afterConvartMaxValue;
        $this->validate_land_area = $afterConvartLandArea->is_convart;

        // convart price
        $afterConvartPrice  = $this->convartPrice($afterSplitRawData->price);
        $this->minimum_price = $afterConvartPrice->afterConvartMinValue;
        $this->maximum_price = $afterConvartPrice->afterConvartMaxValue;
        $this->validate_price = $afterConvartPrice->is_convart;

        // land status
        $this->land_status = $afterSplitRawData->land_status;

        // traffic
        $this->traffic = $afterSplitRawData->traffic;

        // publisher info
        $this->url = $afterSplitRawData->url;
        $this->publication_destination = $afterSplitRawData->publication_destination;
        $this->setProperty($afterSplitRawData);

        // property number
        $this->property_number  = $afterSplitRawData->property_number;
    }

    /**
     * abstract method
     */

    /**
     * split row data. as result AfterSplitRawData of ResultClass is return.
     * detile of split logic will be implemented per each publiser site.
     *
     * @param [type] $rawRowData
     * @return void
     */
    abstract protected function setProperty($afterSplitRawData);
    abstract protected function getAfterSplitRawData($rawRowData);
    abstract protected function convartLocationArea($rawLandArea);
    abstract protected function convartPrice($rawPrice);

    /**
     * Convart location
     *
     * @param [type] $location
     * @return string after conveat location desc
     * @return int town id
     */
    final public static function convartLocation($location)
    {
        /** for example
         *  contents of $splitResult variable
         * 宮城県仙台市太白区四郎丸字神明91番6
         * $prefecture = "宮城県"
         * $prefcityecture = "仙台市太白区"
         * $town = "四郎丸字神明"
         * $townBlock = "9丁目"
         *
         *
         * 宮城県仙台市青葉区木町
         * $prefecture = "宮城県"
         * $prefcityecture = "仙台市青葉区"
         * $town = "木町"
         * $townBlock = ""
         */

        $splitResult = \Location::splitPrefecturesCityTown($location);

        // get each value with using key name
        $prefecture = $splitResult['PREFECTURE'];
        $city = $splitResult['CITY'];
        $town = $splitResult['TOWN'];
        $townBlock = $splitResult['TOWN_BLOCK'];

        // create addrres value and full fullAddrres
        // it is declaration because it don't cleare for content of valuse.
        $addrres = $prefecture . $city . $town;
        $fullAddrres = $addrres . $townBlock;

        $prefectureModel;
        $cityModel;
        $townModelList;
        $town_id = null;

        // it is check there is data in database at each value.
        // if there is not data in data base return false.
        try {
            $prefectureModel = Prefecture::where("name", $prefecture)->first();
            if ($prefectureModel == null) {
                throw new \Exception;
            }

            $cityModel = City::where("name", $city)->where("prefectures_id", $prefectureModel->id)->first();
            if ($cityModel == null) {
                throw new \Exception;
            }

            $townModelList = Town::where("cities_id", $cityModel->id)->get();
            if ($townModelList == null) {
                throw new \Exception;
            }
        } catch (\Exception $e) {
            Log::info("Warning:Scraping CAN NOT GET MODEL" . Carbon::now());
            $result['AFTER_CONVERT_FULL_NAME'] = null;
            $result['ORIGINAL_NAME'] = $location;
            $result['TOWN_ID'] = NULL;
            return $result;
        }

        // addressList from getting master data.
        $addressList;

        foreach ($townModelList as $index => $townModel) {
            $addressList[$index]['AFTER_CONVERT_FULL_NAME'] = $townModel->full_address;
            $addressList[$index]['ORIGINAL_NAME'] = $location;
            $addressList[$index]['TOWN_ID'] = $townModel->id;
        }

        // compare to full address.
        for ($i = 0; $i < count($addressList); $i++) {
            // full macth.
            if ($fullAddrres === $addressList[$i]['AFTER_CONVERT_FULL_NAME']) {
                return $addressList[$i];
            }
        }

        // compare to address excluded block number.
        for ($i = 0; $i < count($addressList); $i++) {
            if ($addrres === $addressList[$i]['AFTER_CONVERT_FULL_NAME']) {
                return $addressList[$i];
            }
        }


        /**
         * Prefix match
         * For exmaple
         * $addressList[$i] : 宮城県仙台市泉区福岡
         * raw data($addrres) : 宮城県仙台市泉区福岡岳山
         *
         * マスターが長い場合のコメントを書く
         *
         *
         */

        $is_contaion_tyoume_in_all_addressList = false;

        for($i = 0; $i < count($addressList); $i++){
            $isExistMas = strpos($addrres,$addressList[$i]['AFTER_CONVERT_FULL_NAME']);
            $isExistSc = strpos($addressList[$i]['AFTER_CONVERT_FULL_NAME'],$addrres);
            // if which one is true.
            if(($isExistMas !== false || $isExistSc !== false) && strpos($addressList[$i]['AFTER_CONVERT_FULL_NAME'],"丁目")){
                $is_contaion_tyoume_in_all_addressList = true;
                Log::warning("Scraping CAN NOT CONVART  isExistMas:$isExistMas isExistSc:$isExistSc address:" . $addressList[$i]['AFTER_CONVERT_FULL_NAME']);
            }
        }

        if ($is_contaion_tyoume_in_all_addressList) {
            Log::warning("Scraping data CAN NOT CONVART has found! Please check the previous log.");
            $result['AFTER_CONVERT_FULL_NAME'] = null;
            $result['ORIGINAL_NAME'] = $location;
            $result['TOWN_ID'] = NULL;
            return $result;
        }

        for($i = 0; $i < count($addressList); $i++){
            $isExistMas = strpos($addrres,$addressList[$i]['AFTER_CONVERT_FULL_NAME']);
            $isExistSc = strpos($addressList[$i]['AFTER_CONVERT_FULL_NAME'],$addrres);

            // if which one is true.
            if ($isExistMas !== false || $isExistSc !== false) {
                Log::warning("Scraping Prefixmatch isExistMas:$isExistMas isExistSc:$isExistSc target address:$addrres");
                return $addressList[$i];
            }
        }

        /**
         * delete character to 字(Zi)を消して再検索
         * データ : 宮城県仙台市泉区福岡字岳山 -> 宮城県仙台市泉区福岡岳山
         *  警告ログ
         */
        $afterConvartTown = str_replace('字', '', $town);
        $deleteZiAddrres = $prefecture . $city . $afterConvartTown;
        $deleteZiFullAddrres = $prefecture . $city . $afterConvartTown . $townBlock;

        for ($i = 0; $i < count($addressList); $i++) {
            if ($deleteZiFullAddrres === $addressList[$i]['AFTER_CONVERT_FULL_NAME']) {
                Log::info("Warning:Scraping Delete Word 字" . Carbon::now());
                return $addressList[$i];
            }
        }

        Log::info("Warning:Scraping CAN NOT CONVART" . Carbon::now());
        $result['AFTER_CONVERT_FULL_NAME'] = null;
        $result['ORIGINAL_NAME'] = $location;
        $result['TOWN_ID'] = NULL;
        return $result;
    }

    final protected function splitData($scrapingValue): array
    {
        $deleteBlank = preg_replace('/\s+/', '', $scrapingValue);
        $afterSprit = preg_split("[~|～]", $deleteBlank);
        if (count($afterSprit) === 1) {
            $afterSprit = preg_split("[・]", $afterSprit[0]);
        }
        return $afterSprit;
    }
}
