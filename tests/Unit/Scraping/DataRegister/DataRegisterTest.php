<?php

namespace Tests\Unit\Scraping\DataRegister;


use App\Console\Scraping\DataRegister\ScrapingDataRegister;
use App\Console\Scraping\DataRow\AtHomeDataRow;
use App\Models\Scraping;

use Tests\Unit\Scraping\DataRegister\Base\BaseDataRegisterTestCase;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Unit\Scraping\DataRow\Base\BaseDataRowTestCase;
use App\Models\Property;
use App\Models\Customer;
use App\Models\CustomerNew;
use App\Models\CustomerDesiredArea;
use App\Models\CustomerFavoriteProperty;

use TestPrefectureSeeder;
use \stdClass;

class DataRegisterTest extends BaseDataRegisterTestCase
{

    protected function setDataRowClass(){
        return AtHomeDataRow::Class;
    }

    protected function setDataRegisterClass(){
        return ScrapingDataRegister::Class;
    }

    protected function setScrapingTableOrm(){
        return Scraping::Class;
    }

    protected function setPropertyOrm(){
        return Property::Class;
    }

     
    /**
     * This method is cretae new at status of new Property.
     * but , now when new property is added by scratping, it property status is "APPROVAL_PENDING".
     * Thereforce createNews(New property) is not working.
    public function createNews(){
        // add cusomer desiredarea
        CustomerDesiredArea::Create(['customers_id' => 1, 'cities_id' => 1,'towns_id' => 1,]);
        CustomerDesiredArea::Create(['customers_id' => 2, 'cities_id' => 1,'towns_id' => null]);

        $stdPropertyArray =   [
            ['price' => "1,960万円",'landarea'  => "176.91m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "3,260万円",'landarea'  => "136.30m²","location"  => "宮城県仙台市青葉区角五郎１丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "3,200万円",'landarea'  => "136.31m²","location"  => "宮城県仙台市青葉区角五郎１丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""]
        ];

        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();
        
        for($i = 1 ; $i < 3 ; $i++){
            $rowCount = CustomerNew::where('customers_id',$i)->where('type',CustomerNew::ADD_PROPERTY)->count();
            $result = false;
            // it is expected user 1 is 1row and user 2 is 2row in exsitng news table.
            switch($i){
                // customer id is 1
                case "1" :
                    if($rowCount == 1){$result = true;}
                    break;

                // customer id is 2
                case "2" :
                    if($rowCount == 2){$result = true;}
                    break;
            }
            
            if(!$result){
                $this->assertTrue(false);     
            }
        }
        $this->assertTrue(true);
    }
    */

    /**
     * @test
     * 
     *
     * @return void
     */
    public function createFinishStatusNews(){

        CustomerDesiredArea::Create(['customers_id' => 1, 'cities_id' => 1,'cities_area_id' => 1,]);
        CustomerDesiredArea::Create(['customers_id' => 2, 'cities_id' => 1,'cities_area_id' => null]);

        $stdPropertyArray =   [
            ['price' => "1,960万円",'landarea'  => "146.91m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test01","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "3,260万円",'landarea'  => "106.30m²","location"  => "宮城県仙台市青葉区角五郎１丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test02","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "3,200万円",'landarea'  => "106.31m²","location"  => "宮城県仙台市青葉区角五郎１丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test03","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "3,200万円",'landarea'  => "106.31m²","location"  => "宮城県仙台市青葉区角五郎１丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test04","publication_destination" => "atHome","land_status" => "","property_no" =>""]
        ];

        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        CustomerFavoriteProperty::Create(['customers_id' => 1, 'properties_id' => 1]);
        CustomerFavoriteProperty::Create(['customers_id' => 2, 'properties_id' => 2]);

        $stdPropertyArray =   [
            ['price' => "1,960万円",'landarea'  => "146.91m²","location"  => "宮城県仙台市宮城野区松岡町","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test05","publication_destination" => "atHome","land_status" => "","property_no" =>""],
        ];
        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        for($i = 1 ; $i < 3 ; $i++){
            $rowCount = CustomerNew::where('customers_id',$i)->where('type',CustomerNew::PROPERTY_END)->count();
            $result = false;
            // it is expected user 1 is 1row and user 2 is 2row in exsitng news table.
            switch($i){
                // customer id is 1
                case "1" :
                    if($rowCount == 1){$result = true;}
                    break;

                // customer id is 2
                case "2" :
                    if($rowCount == 1){$result = true;}
                    break;
            }       
            if(!$result){
                $this->assertTrue(false);     
            }
        }
        $this->assertTrue(true);
    }

      /**
     * @test
     * 
     *
     * @return void
     */
    public function thereAreSamePropertylPattern()
    {
        $stdPropertyArray =   [
            ['price' => "260万円",'landarea'  => "136.30m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test13","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "260万円",'landarea'  => "136.30m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test14","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "260万円",'landarea'  => "136.30m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test15","publication_destination" => "atHome","land_status" => "","property_no" =>""]
        ];
        
        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $expectResultArray =   [
            ['minimum_price' => "2600000",'minimum_land_area' => "136.30","location" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test15","publication_destination" => "SUUMO","land_status" => "","property_no" =>"","property_convert_status_id" =>"0"]        ];
        $propertyModels = $this->getPropertyModelFromDatabase($expectResultArray[0]);

        if(count($propertyModels) != 1){
            $this->assertTrue(false);
        }
        $this->assertTrue(true);
    }


    
    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertNormalPattern()
    {
        $stdPropertyArray =   [
            ['price' => "3,260万円",'landarea'  => "136.30m²","location"  => "宮城県仙台市青葉区角五郎２丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test1","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "1,960万円",'landarea'  => "176.91m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test2","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "3,200万円",'landarea'  => "136.31m²","location"  => "宮城県仙台市青葉区角五郎１丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test3","publication_destination" => "atHome","land_status" => "","property_no" =>""]
        ];
        
        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $expectResultArray =   [
            ['minimum_price' => "32600000",'minimum_land_area' => "136.30","location" => "宮城県仙台市青葉区角五郎二丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test1","publication_destination" => "atHome","land_status" => "","property_no" =>"","property_convert_status_id" =>"0"],
            ['minimum_price' => "19600000",'minimum_land_area' => "176.91","location" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test2","publication_destination" => "atHome","land_status" => "","property_no" =>"","property_convert_status_id" =>"0"],
            ['minimum_price' => "32000000",'minimum_land_area' => "136.31","location" => "宮城県仙台市青葉区角五郎一丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test3","publication_destination" => "atHome","land_status" => "","property_no" =>"","property_convert_status_id" =>"0"]
        ];

        $this->compareToResultArray($expectResultArray,true);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongLandAreaPattern()
    {
        $stdPropertyArray =  [['price' => "2,150万円",'landarea'  => "272.42dm²","location" => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test4","publication_destination" => "atHome","land_status" => "","property_no" =>""]];
        
        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $expectResultArray = [['minimum_price' => "21500000",'minimum_land_area' => null    ,"location" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test4","publication_destination" => "atHome","land_status" => "","property_no" =>"","property_convert_status_id" =>"300"]];
      
        $this->compareToResultArray($expectResultArray,true);
    }

     /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongLocationPattern()
    {
        $stdPropertyArray = [
            ['price' => "8,000万円",'landarea'  => "162.66m²","location"  => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic"    => "東北線　仙台停歩　4分/バス　28分","URL" => "test5","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "9,000万円",'landarea'  => "162.66m²","location"  => "宮城県仙台市青葉区角五郎３丁目","traffic"    => "東北線　仙台停歩　4分/バス　28分","URL" => "test6","publication_destination" => "atHome","land_status" => "","property_no" =>""],
        ];

        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $expectResultArray =  [
            ['minimum_price' => "80000000",'minimum_land_area' => "162.66","location" => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test5","publication_destination" => "atHome","land_status" => "","property_no" =>"","property_convert_status_id" =>"100"],
            ['minimum_price' => "90000000",'minimum_land_area' => "162.66","location" => "宮城県仙台市青葉区角五郎３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test6","publication_destination" => "atHome","land_status" => "","property_no" =>"","property_convert_status_id" =>"100"]
        ];

        $this->compareToResultArray($expectResultArray,true);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongPricePatterna()
    {       
        $stdPropertyArray =  [['price' => "2,050万円",'landarea'  => "272.42dm²","location" => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test7","publication_destination" => "atHome","land_status" => "","property_no" =>""]];
        
        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $expectResultArray = [['minimum_price' => "20500000",'minimum_land_area' => null    ,"location" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url7" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>"","property_convert_status_id" =>"300"]];
      
        $this->compareToResultArray($expectResultArray,true);
    }

      /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongStatus()
    {       
        $expectResultArray =   [['minimum_price' => "32600000",'minimum_land_area' => "136.30","location" => "宮城県仙台市青葉区角五郎二丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url8" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>"","property_convert_status_id" =>"0"]];
        $propertyModels = $this->getPropertyModelFromDatabase($expectResultArray[0]);
        
        foreach($propertyModels as $propertyModel){
            $propertyModel->property_convert_status_id = 1000;
            $propertyModel->save();
        }

        $stdPropertyArray =   [
            ['price' => "3,260万円",'landarea'  => "136.30m²","location"  => "宮城県仙台市青葉区角五郎２丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test8","publication_destination" => "atHome","land_status" => "","property_no" =>""],
        ];
        
        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $propertyModels = $this->getPropertyModelFromDatabase($expectResultArray[0]);
        if($propertyModels->count() != 2){
            $this->assertTrue(false);
        }

        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $propertyModels = $this->getPropertyModelFromDatabase($expectResultArray[0]);
        if($propertyModels->count() != 2){
            $this->assertTrue(false);
        }
        $this->assertTrue(true);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function changePropertyStatusTopublishPattern()
    {
        $stdPropertyArray =   [
            ['price' => "1,960万円",'landarea'  => "176.91m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test9","publication_destination" => "atHome","land_status" => "","property_no" =>""]
        ];

        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $expectResultArray =   [
            ['minimum_price' => "19600000",'minimum_land_area' => "176.91","location" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test9","publication_destination" => "atHome","land_status" => "","property_no" =>"","property_convert_status_id" =>"0"]        ];
        $propertyModels = $this->getPropertyModelFromDatabase($expectResultArray[0]);
        
        foreach($propertyModels as $propertyModel){
            if($propertyModel->property_statuses_id != 2){
                $this->assertTrue(false);
            }
        }

        $stdPropertyArray =   [
            ['price' => "1,960万円",'landarea'  => "176.91m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test10","publication_destination" => "SUUMO","land_status" => "","property_no" =>""]
        ];

        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $expectResultArray =   [
            ['minimum_price' => "19600000",'minimum_land_area' => "176.91","location" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test10","publication_destination" => "SUUMO","land_status" => "","property_no" =>"","property_convert_status_id" =>"0"]        ];
        $propertyModels = $this->getPropertyModelFromDatabase($expectResultArray[0]);
        
        foreach($propertyModels as $propertyModel){
            if($propertyModel->property_statuses_id != 2){
                $this->assertTrue(false);
            }
        }

        $this->assertTrue(true);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function updatePropertyPriceURLPattern()
    {  
        CustomerFavoriteProperty::Create(['customers_id' => 2, 'properties_id' => 5]);
        $stdPropertyArray =   [
            ['price' => "3,960万円",'landarea'  => "176.91m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test13","publication_destination" => "atHome","land_status" => "","property_no" =>""]
        ];

        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $stdPropertyArray =   [
            ['price' => "7,960万円",'landarea'  => "176.91m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test13","publication_destination" => "SUUMO","land_status" => "","property_no" =>""]
        ];

        $this->addDataToDataRegisterObject($stdPropertyArray,true);
        $this->storeToDataBase();

        $expectResultArray =   [
            ['minimum_price' => "79600000",'minimum_land_area' => "176.91","location" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test13","publication_destination" => "SUUMO","land_status" => "","property_no" =>"","property_convert_status_id" =>"0"]        ];
        $propertyModels = $this->getPropertyModelFromDatabase($expectResultArray[0]);
        
        foreach($propertyModels as $propertyModel){
            if($propertyModel->property_statuses_id != 2){
                $this->assertTrue(false);
            }
        }

        $this->assertTrue(true);
    }
}
