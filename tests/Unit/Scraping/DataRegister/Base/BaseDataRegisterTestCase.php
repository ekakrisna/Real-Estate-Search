<?php

namespace Tests\Unit\Scraping\DataRegister\Base;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Unit\Scraping\DataRow\Base\BaseDataRowTestCase;
use Illuminate\Support\Facades\Artisan;

use TestPrefectureSeeder;
use Tests\TestCase;
use \stdClass;

abstract class BaseDataRegisterTestCase extends TestCase
{   
    protected $dataRegisterObject = null;
    protected static $setUpHasRunOnce = false;

    // execute database seeder and migration only first time. 
    public function setUp(): void
    {
        parent::setUp();
        if(!static::$setUpHasRunOnce){
            Artisan::call('migrate:fresh');
            Artisan::call('db:seed'); 
            static::$setUpHasRunOnce = true;
        }
    }

    abstract protected function setDataRowClass();
    abstract protected function setDataRegisterClass();
    abstract protected function setScrapingTableOrm();
    abstract protected function setPropertyOrm();

    protected function compareToResultArray($arrayExpectedResult,$isConvertStdClass = false){        
        // compare atHome object to property of expected Result. 
        foreach($arrayExpectedResult as $expectedResult){

            /**
             * Check existence of scraping-related table.
             */
            $scrapingModel =  $this->getScrapingModelFromDatabase($expectedResult,$isConvertStdClass);
            if($scrapingModel == null){
                $this->assertTrue(false);
            }

            $result = $this->compareToModel($scrapingModel,$expectedResult);
            if(!$result){
                $this->assertTrue(false);
            }

            $scrapingPublishCount = $scrapingModel->scraping_publish()->count();
            if($scrapingPublishCount == 0){
                $this->assertTrue(false);
            }

            $scrapingLogCount = $scrapingModel->scraping_log()->count();
            if($scrapingLogCount == 0){
                $this->assertTrue(false);
            }

            /**
             * Check existence of scraping-related table.
             */
            $propertyModel = $scrapingModel->property()->first();
            if($propertyModel == null){
                $this->assertTrue(false);
            }

            $result = $this->compareToModel($propertyModel,$expectedResult);
            if(!$result){
                $this->assertTrue(false);
            }        
            
            $propertyPublishCount = $propertyModel->property_publish()->count();
            if($propertyPublishCount == 0){
                $this->assertTrue(false);
            }

            $propertyLogCount = $propertyModel->property_log_activities()->count();
            if($scrapingLogCount == 0){
                $this->assertTrue(false);
            }
        }
        $this->assertTrue(true);     
    }


    /**
     * Add data to dataRegisterObject object.
     * dataRegisterObject class is stored multiple dataRowClass object. 
     * when update property table and scraping table, usigng datarow calss stored. 
     * 
     * @param [type] $arrayForConvartArray
     * @param [type] $isConvertStdClass 
     * @return void
     */
    final protected function addDataToDataRegisterObject($arrayForConvartArray,$isConvertStdClass){
        $ScrapingDataRegisterName = $this->setDataRegisterClass();
        $this->dataRegisterObject = new $ScrapingDataRegisterName();
        foreach($arrayForConvartArray as $forConvartArray) {
            $dataRowClass =  $this->createDataRowObject($forConvartArray,$isConvertStdClass);
            $this->dataRegisterObject->addData($dataRowClass);
        }
    }

    /**
     * Update Database using DataRow class stored in dataRegisterObject.
     * 
     * @return void
     */
    final protected function storeToDataBase(){
        $this->dataRegisterObject->dataRegister();
        $this->dataRegisterObject = null;
    }

    /**
     * get scraping data from Database.
     *
     * @param [type] $arrayForConvart
     * @param [type] $isConvertStdClass
     * @return void
     */
    final protected function getScrapingDataFromDatabase($arrayForConvart,$isConvertStdClass = false){
        $dataRowClass = $this->createDataRowObject($arrayForConvart,$isConvertStdClass);
        $scarapingObject = $this->setScrapingTableOrm()::where('location',$dataRowClass->location)
        ->where('minimum_price',$dataRowClass->minimum_price)
        ->where('maximum_price',$dataRowClass->maximum_price)
        ->where('minimum_land_area',$dataRowClass->minimum_land_area)
        ->where('maximum_land_area',$dataRowClass->maximum_land_area)->first();
        return $scarapingObject;
    }

    /**
     * get scraping data from Database.
     *
     * @param [type] $arrayForConvart
     * @param [type] $isConvertStdClass
     * @return void
     */
    final protected function getScrapingModelFromDatabase($arrayForConvart){

        $location = null;
        $minimum_price = null;
        $maximum_price = null;
        $minimum_land_area = null;
        $maximum_land_area = null;

        if(array_key_exists("location",$arrayForConvart)){
            $location = $arrayForConvart["location"];
        }
        
        if(array_key_exists("minimum_price",$arrayForConvart)){
            $minimum_price = $arrayForConvart["minimum_price"];
        }
        
        if(array_key_exists("maximum_price",$arrayForConvart)){
            $maximum_price = $arrayForConvart["maximum_price"];
        }
        
        if(array_key_exists("minimum_land_area",$arrayForConvart)){
            $minimum_land_area = $arrayForConvart["minimum_land_area"];
        }
        
        if(array_key_exists("maximum_land_area",$arrayForConvart)){
            $maximum_land_area = $arrayForConvart["maximum_land_area"];
        }

        $scarapingObject = $this->setScrapingTableOrm()::where('location',$location)
        ->where('minimum_price',$minimum_price)
        ->where('maximum_price',$maximum_price)
        ->where('minimum_land_area',$minimum_land_area)
        ->where('maximum_land_area',$maximum_land_area)->first();
        return $scarapingObject;
    }

    /**
     * get scraping data from Database.
     *
     * @param [type] $arrayForConvart
     * @param [type] $isConvertStdClass
     * @return void
     */
    final protected function getPropertyModelFromDatabase($arrayForConvart){

        $location = null;
        $minimum_price = null;
        $maximum_price = null;
        $minimum_land_area = null;
        $maximum_land_area = null;

        if(array_key_exists("location",$arrayForConvart)){
            $location = $arrayForConvart["location"];
        }
        
        if(array_key_exists("minimum_price",$arrayForConvart)){
            $minimum_price = $arrayForConvart["minimum_price"];
        }
        
        if(array_key_exists("maximum_price",$arrayForConvart)){
            $maximum_price = $arrayForConvart["maximum_price"];
        }
        
        if(array_key_exists("minimum_land_area",$arrayForConvart)){
            $minimum_land_area = $arrayForConvart["minimum_land_area"];
        }
        
        if(array_key_exists("maximum_land_area",$arrayForConvart)){
            $maximum_land_area = $arrayForConvart["maximum_land_area"];
        }

        $scarapingObject = $this->setPropertyOrm()::where('location',$location)
        ->where('minimum_price',$minimum_price)
        ->where('maximum_price',$maximum_price)
        ->where('minimum_land_area',$minimum_land_area)
        ->where('maximum_land_area',$maximum_land_area)->get();
        return $scarapingObject;
    }

    /**
     * Create DataRowClass to store in DataRegister class.
     *
     * @return void
     */
    private function createDataRowObject($arrayForConvart,$isConvertStdClass = false){
        
        // dataRow class name and create dataRow object.
        $dataRowClass = $this->setDataRowClass();
        $dataRowClassObject = new $dataRowClass();

        // if "arrayForConvart" of arg need to convert to Std class.
        if($isConvertStdClass){
            $stdClassForConvert = $this->toStdClass($arrayForConvart);
            $dataRowClassObject->convertFromScrapingData($stdClassForConvert);
        }
        else{
            $dataRowClassObject->convertFromScrapingData($arrayForConvart);
        }
        return $dataRowClassObject;
    }

    /**
     * Create Stdclass for convarting dataRow class.
     *
     * @return void
     */
    private function toStdClass($arrayRow){
        $newStdClass = new stdClass();
        foreach($arrayRow as $key => $value){
            $newStdClass->$key = $value;
        }
        return $newStdClass;
    }

    private function compareToModel($model,$arrayExpectedResult){
        foreach($model as $key => $value){
            
            if(!array_key_exists($key,$arrayExpectedResult)){
                continue;
            }

            if($arrayExpectedResult[$key] != $value){
                return false;
            }
        }
        return true;
    }

}
