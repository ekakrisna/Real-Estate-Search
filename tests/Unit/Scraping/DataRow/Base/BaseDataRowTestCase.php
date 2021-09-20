<?php

namespace Tests\Unit\Scraping\DataRow\Base;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use TestPrefectureSeeder;
use \stdClass;
use App\Console\Scraping\DataRow\Base\BaseScrapingRow;
use Illuminate\Support\Facades\Artisan;

abstract class BaseDataRowTestCase extends TestCase
{
    private $keyForPreConvaretClass = ['price','landarea','location','traffic','url','publication_destination','land_status','property_no']; 
    //private $keyForPreConvaretClass = ['price','minimum_land_area','landarea','location','traffic','url','publication_destination','land_status','property_no']; 
    protected static $setUpHasRunOnce = false;
    //use RefreshDatabase;

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

    /**
     * Undocumented function
     *
     * @param [type] $arrayForConvart
     * @param [type] $arrayExpectedResult
     * @return void
     */
    protected function compareToResultArray($arrayForConvart,$arrayExpectedResult,$isConvertStdClass = true){
        
        $stdClassForConvert = null;
        if($isConvertStdClass){
            $stdClassForConvert = $this->toStdClass($arrayForConvart);
        }
    
        // create atHome object and execute ConvertFunction.
        $dataRowClassName = $this->setDataRowClass();
        $dataRowClass = new $dataRowClassName();

        if($stdClassForConvert == null){
            $dataRowClass->convertFromScrapingData($arrayForConvart);
        }
        else{
            $dataRowClass->convertFromScrapingData($stdClassForConvert);
        }

        // compare atHome object to property of expected Result. 
        $isMatchExpectedResult = $this->compareToDataRowObject($dataRowClass,$arrayExpectedResult);
        $this->assertTrue($isMatchExpectedResult);     
    }

    /**
     * Create Stdclass for convarting data row class.
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

    /**
     * Compare dataRow class to property of expected result array.
     *
     * @return void
     */
    private function compareToDataRowObject($dataRowObject,$arrayExpectedResult){
        foreach($dataRowObject as $key => $value){
            
            if(!array_key_exists($key,$arrayExpectedResult)){
                continue;
            }

            if($arrayExpectedResult[$key] != $value){
                return false;
            }
        }
        return true;
    }

    protected function createStdClassForConvert($price,$landarea,$location){
        $newStdClass = new stdClass();
        for($i = 0 ; $i < count($keyForPreConvaretClass) ; $i){
            $value = "";
            switch($keyForPreConvaretClass[$i]){
                case 'price' :
                    $value = $price;
                    break;
                case 'landarea' :
                    $value = $landarea;
                    break;
                case 'location' :
                    $value = $location;
                    break;
                default:
                    $value = $i;
                    break;
            }
            $newStdClass->$keyForPreConvaretClass[$i] = $value;
        }
    }
}