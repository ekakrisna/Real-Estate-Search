<?php

namespace App\Console\Scraping\DataRow;

use App\Console\Scraping\DataRow\Base\BaseScrapingRow;
use App\Console\Scraping\DataRow\ResultClass\ResultConvartRange;
use App\Console\Scraping\DataRow\ResultClass\AfterSplitRawData;


class AtHomeDataRow extends BaseScrapingRow
{    
    protected function getAfterSplitRawData($rawRowData){
        $splitResult = new AfterSplitRawData();
        $splitResult->price = preg_replace('/\s+/', '', $rawRowData->price);
        $splitResult->landArea = preg_replace('/\s+/', '', $rawRowData->landarea);
        $splitResult->location = preg_replace('/\s+/', '', $rawRowData->location);
        $splitResult->land_status = preg_replace('/\s+/', '', $rawRowData->land_status);
        $splitResult->traffic = preg_replace('/\s+/', '', $rawRowData->traffic);
        $splitResult->url = preg_replace('/\s+/', '', $rawRowData->URL);
        $splitResult->publication_destination = preg_replace('/\s+/', '', $rawRowData->publication_destination);
        $splitResult->property_number = preg_replace('/\s+/', '', $rawRowData->property_no);

        if($splitResult->price === "" && $splitResult->landArea === "" && $splitResult->location === "" && $splitResult->land_status ===""  && $splitResult->traffic === ""){
            $splitResult->isCorrectlyGet = false;
        } 

        return $splitResult;
    }

    protected function setProperty($afterSplitRawData){
    }

    protected function convartPrice($rawPrice){

        // create variable for retrun value.
        $convartResult = new ResultConvartRange();
  
        $price = str_replace(",","",$rawPrice);
        $price = str_replace("円","",$price);
        $price = str_replace("万","",$price);
        $posDot = strpos($price,'.');
        $addStr = "0000";
        if($posDot > 0){
            $lenght = strlen($price) - $posDot - 1;
            for($i = 0 ; $i < $lenght; $i++){
                $addStr = mb_substr($addStr, 1);
            }
            $price = str_replace(".","",$price);
        }
    
        $price = $price.$addStr;

        if(!is_numeric($price)){
            $convartResult->is_convart = false;
            $price = null;
        }
        $convartResult->afterConvartMinValue = $price == null ? null :$price ;        
        return $convartResult;
    }
    
    protected function convartLocationArea($rawLand){
        // create variable for retrun value.
        $convartResult = new ResultConvartRange();

        $LnadArea = str_replace(",","",$rawLand);
        $posDot = strpos($LnadArea,'m²');
        $LnadArea = mb_substr($LnadArea,0,$posDot);

        if(!is_numeric($LnadArea)){
            $convartResult->is_convart = false;
            $LnadArea = null;
        }
        $convartResult->afterConvartMinValue = $LnadArea == null ? null :$LnadArea ;
          
        return $convartResult;
    }
}