<?php
namespace App\Lib;

use Illuminate\Support\Facades\Facade;

class Location
{
    public function __construct()
    {
    }

    /**
     * Split location to prefectures,city and town.
     *
     * @param string $location 
     * @return array data splited to array.
     * [PREFECTURES] [CITY] [TOWN]
     * 
     */
    public function splitPrefecturesCityTown($location){
        $result;
        $result['PREFECTURE'] ="";
        $result['CITY'] ="";
        $result['TOWN'] ="";
        $result['TOWN_BLOCK'] ="";

        $kanjiNumber = array(0=>'十', 1=>'一', 2=>'二', 3=>'三', 4=>'四', 5=>'五', 6=>'六', 7=>'七', 8=>'八', 9=>'九');
  
        $afterLocation  = str_replace('（地図を見る）','',$location);
        $afterLocation  = str_replace('-','',$afterLocation);
        
        $prefecturesPattern = '/(...??[都道府県])((?:旭川|伊達|石狩|盛岡|奥州|田村|南相馬|那須塩原|東村山|武蔵村山|羽村|十日町|上越|富山|野々市|大町|蒲郡|四日市|姫路|大和郡山|廿日市|下松|岩国|田川|大村)市|.+?郡(?:玉村|大町|.).*?[町村]|.+?市.+?区|.+?[市区町村])(.+)/u'; 
        $splitResult = preg_split($prefecturesPattern,$afterLocation,-1,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        if(count($splitResult) < 3){
            return $result;
        }
        
        $result['PREFECTURE'] = $splitResult[0];
        $result['CITY'] = $splitResult[1];
        $scrapingTown = $splitResult[2];

        $pattern = "/(?=[０-９|0-9])/u";
        $splitResult = preg_split($pattern,$scrapingTown,-1,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
        $result['TOWN'] = $splitResult[0];

        $townBlock ="";
        if(count($splitResult) > 1){
            $afterAttachedValue = "";

            $pattern = "/([０-９|0-9])/u";
            $splitResult = preg_split($pattern,$splitResult[1],-1,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            $afterResult = "";

            for($i = 0 ; $i < count($splitResult); $i++){
                //if(!is_numeric($splitResult[$i])){continue;}
                $tmpValue= $this->convartFullWidth2HalfWidth($splitResult[$i]);
                if($tmpValue == null){continue;}
                if($i < 2){
                    $afterResult.=$kanjiNumber[$tmpValue];
                }
            }
                
            $townBlock.=$afterResult. "丁目";        
        } 

        $result['TOWN_BLOCK'] = $townBlock;
        return $result;
    } 

    private function convartFullWidth2HalfWidth($number){
        $returnValue = null;

        $halfWidthNumber= mb_convert_kana($number,"n");
        if(is_numeric($halfWidthNumber)){
            $returnValue = $halfWidthNumber;
        }
        return $returnValue;
    }
}