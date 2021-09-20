<?php

namespace Tests\Unit\Scraping\DataRow;

use App\Console\Scraping\DataRow\ReinsDataRow;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Unit\Scraping\DataRow\Base\BaseDataRowTestCase;

class ReinsTest extends BaseDataRowTestCase
{
    protected function setDataRowClass(){
        return ReinsDataRow::Class;
    }
    
    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongPricePattern()
    {
        $stdPropertyArray = ["1,300d万円", "270.36㎡","宮城県仙台市青葉区荒巻本沢３丁目", "東北線　仙台停歩　4分/バス　28分",1000];
        $expectResultArray = ['minimum_price' => null,'minimum_land_area' => "270.36","location_full" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","property_number" =>"1000"];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray,false);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongLandAreaPattern()
    {
        $stdPropertyArray =  ["2,150万円","272.42d㎡","宮城県仙台市青葉区荒巻本沢３丁目","東北線　仙台停歩　4分/バス　28分",1000];
        $expectResultArray = ['minimum_price' => "21500000",'minimum_land_area' => null    ,"location_full" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","property_number" =>"1000"];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray,false);
    }

     /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongLocationPattern()
    {
        $stdPropertyArray =  ["8,000万円","162.66㎡","宮城県仙台市ERROR青葉区上杉６丁目d","東北線　仙台停歩　4分/バス　28分",1000];
        $expectResultArray = ['minimum_price' => "80000000",'minimum_land_area' => "162.66","location_original" => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic" => "東北線　仙台停歩　4分/バス　28分","property_number" =>"1000"];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray,false);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertPriceincludedDecimalPointPattern()
    {
        
        $stdPropertyArray =  ["8,000.09万円","162.66㎡","宮城県仙台市ERROR青葉区上杉６丁目d","東北線　仙台停歩　4分/バス　28分",1000];
        $expectResultArray = ['minimum_price' => "80000900",'minimum_land_area' => "162.66","location_original" => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic" => "東北線　仙台停歩　4分/バス　28分","property_number" =>"1000"];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray,false);
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
            ["1,960万円","176.91㎡","宮城県仙台市青葉区荒巻本沢３丁目","東北線　仙台停歩　4分/バス　28分",1000],
            ["3,260万円","136.30㎡","宮城県仙台市青葉区角五郎１丁目",  "東北線　仙台停歩　4分/バス　28分",1000],
            ["3,200万円","136.31㎡","宮城県仙台市青葉区角五郎１丁目",  "東北線　仙台停歩　4分/バス　28分",1000]
        ];

        $expectResultArray =   [
            ['minimum_price' => "19600000",'minimum_land_area' => "176.91","location_full" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","property_number" =>"1000"],
            ['minimum_price' => "32600000",'minimum_land_area' => "136.30","location_full" => "宮城県仙台市青葉区角五郎一丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","property_number" =>"1000"],
            ['minimum_price' => "32000000",'minimum_land_area' => "136.31","location_full" => "宮城県仙台市青葉区角五郎一丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","property_number" =>"1000"]
        ];

        for($i = 0 ; $i < count($stdPropertyArray); $i++){
            $this->compareToResultArray($stdPropertyArray[$i],$expectResultArray[$i],false);
        }
    }

}
