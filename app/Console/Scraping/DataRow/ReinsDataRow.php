<?php

namespace App\Console\Scraping\DataRow;

use App\Console\Scraping\DataRow\Base\BaseScrapingRow;
use App\Console\Scraping\DataRow\ResultClass\ResultConvartRange;
use App\Console\Scraping\DataRow\ResultClass\AfterSplitRawData;


class ReinsDataRow extends BaseScrapingRow
{    
    protected function getAfterSplitRawData($rawRowData){
        $splitResult = new AfterSplitRawData();
        $splitResult->price = $rawRowData[0];
        $splitResult->landArea =$rawRowData[1];
        $splitResult->location = $rawRowData[2];
        $splitResult->traffic = $rawRowData[3];
        $splitResult->property_number = $rawRowData[4];
        $splitResult->publication_destination ="REINS";

        if($splitResult->price === "" && $splitResult->landArea === "" && $splitResult->location === ""  && $splitResult->traffic === ""){
            $splitResult->isCorrectlyGet = false;
        } 

        return $splitResult;
    }

    protected function setProperty($afterSplitRawData){
        // nothing coding 
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

    protected function convartLocationArea($rawLandArea){
        // create variable for retrun value..
        $convartResult = new ResultConvartRange();

        $LnadArea = str_replace(",","",$rawLandArea);
        $posDot = strpos($LnadArea,'㎡');
        $LnadArea = mb_substr($LnadArea,0,$posDot);

        if(!is_numeric($LnadArea)){
            $convartResult->is_convart = false;
            $LnadArea = null;
        }

        $convartResult->afterConvartMinValue = $LnadArea == null ? null :$LnadArea ;    
        return $convartResult;
    }
}