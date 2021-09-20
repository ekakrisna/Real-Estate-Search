<?php

namespace Tests\Unit\Scraping\DataRow;

use App\Console\Scraping\DataRow\AtHomeDataRow;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\Unit\Scraping\DataRow\Base\BaseDataRowTestCase;

use TestPrefectureSeeder;
use \stdClass;

class AtHomeTest extends BaseDataRowTestCase
{
    protected function setDataRowClass(){
        return AtHomeDataRow::Class;
    }
    
    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongPricePattern()
    {
        $stdPropertyArray = ['price' => "1,300d万円",'landarea' => "270.36m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $expectResultArray = ['minimum_price' => null, 'minimum_land_area' => "270.36","location_full" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongLandAreaPattern()
    {
        $stdPropertyArray =  ['price' => "2,150万円",'landarea'  => "272.42dm²","location" => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $expectResultArray = ['minimum_price' => "21500000",'minimum_land_area' => null    ,"location_full" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray);
    }

     /**
     * @test
     * 
     *
     * @return void
     */
    public function convertWrongLocationPattern()
    {
        $stdPropertyArray =  ['price' => "8,000万円",'landarea'  => "162.66m²","location"  => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic"    => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $expectResultArray =   ['minimum_price' => "80000000",'minimum_land_area' => "162.66","location_original" => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray);
    }

     /**
     * @test
     * 
     *
     * @return void
     */
    public function convertMilionPattern()
    {
        $stdPropertyArray =  ['price' => "10,0500万円",'landarea'  => "162.66m²","location"  => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic"    => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $expectResultArray =   ['minimum_price' => "1005000000",'minimum_land_area' => "162.66","location_original" => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertMilionPattern1()
    {
        $stdPropertyArray =  ['price' => "1,5000万円",'landarea'  => "162.66m²","location"  => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic"    => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $expectResultArray =   ['minimum_price' => "150000000",'minimum_land_area' => "162.66","location_original" => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray);
    }

    /**
     * @test
     * 
     *
     * @return void
     */
    public function convertPriceincludedDecimalPointPattern()
    {  
        $stdPropertyArray =  ['price' => "8,000.09万円",'landarea'  => "162.66m²","location"  => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic"    => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""];
        $expectResultArray = ['minimum_price' => "80000900",'minimum_land_area' => "162.66","location_original" => "宮城県仙台市ERROR青葉区上杉６丁目d","traffic" => "東北線　仙台停歩　4分/バス　28分","property_number" =>""];
        $this->compareToResultArray($stdPropertyArray,$expectResultArray);
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
            ['price' => "1,960万円",'landarea'  => "176.91m²","location"  => "宮城県仙台市青葉区荒巻本沢３丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "3,260万円",'landarea'  => "136.30m²","location"  => "宮城県仙台市青葉区角五郎１丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['price' => "3,200万円",'landarea'  => "136.31m²","location"  => "宮城県仙台市青葉区角五郎１丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","URL" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""]
        ];

        $expectResultArray =   [
            ['minimum_price' => "19600000",'minimum_land_area' => "176.91","location_full" => "宮城県仙台市青葉区荒巻本沢三丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['minimum_price' => "32600000",'minimum_land_area' => "136.30","location_full" => "宮城県仙台市青葉区角五郎一丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""],
            ['minimum_price' => "32000000",'minimum_land_area' => "136.31","location_full" => "宮城県仙台市青葉区角五郎一丁目","traffic" => "東北線　仙台停歩　4分/バス　28分","url" => "test","publication_destination" => "atHome","land_status" => "","property_no" =>""]
        ];

        for($i = 0 ; $i < count($stdPropertyArray); $i++){
            $this->compareToResultArray($stdPropertyArray[$i],$expectResultArray[$i]);
        }
    }

}
