<?php

namespace App\Console\Scraping\DataRow;

use App\Console\Scraping\DataRow\Base\BaseScrapingRow;
use App\Console\Scraping\DataRow\ResultClass\ResultConvartRange;
use App\Console\Scraping\DataRow\ResultClass\AfterSplitRawData;


class SuumoDataRow extends BaseScrapingRow
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

        if($splitResult->price === "" && $splitResult->landArea === "" && $splitResult->location === "" && $splitResult->land_status ===""  && $splitResult->traffic === ""){
            $splitResult->isCorrectlyGet = false;
        } 

        return $splitResult;
    }

    protected function setProperty($afterSplitRawData){

    }

    protected function convartPrice($rawPrice){

        $rawPriceArray = $this->splitData($rawPrice);
        // create variable for retrun value.
        $convartResult = new ResultConvartRange();
        for($i = 0 ; $i < count($rawPriceArray); $i++){
            if($i == 2){
                break;
            }
            
            $price = str_replace(",","",$rawPriceArray[$i]);
            $price = str_replace("円","",$price);
            $existsMillion = strpos($price,'億');
            $existsThousand = strpos($price,'万');
            $countThousand = substr_count($price,'万');
            
            // exsample : 5億1000万　→　510000万

            // 5億円
            // 4億100万円
            // 4億1000万円
            $milionPrice = "";
            if($existsMillion){
                $pattern = "/(\d{1,4}億)/u";
                $splitResult = preg_split($pattern,$price,-1,PREG_SPLIT_DELIM_CAPTURE| PREG_SPLIT_NO_EMPTY);
                $milionPrice = str_replace("億","",$splitResult[0]);
            }

            $thousandPrice = "";
            if($existsThousand){
                $pattern = "/(\d{1,4}万)/u";
                $splitResult = preg_split($pattern,$price,-1,PREG_SPLIT_DELIM_CAPTURE| PREG_SPLIT_NO_EMPTY);
                if($existsMillion){
                    $thousandPrice = str_replace('万',"",$splitResult[1]);
                }
                else{
                    $thousandPrice = str_replace('万',"",$splitResult[0]);
                }
               
                $countOfMan = mb_strlen($thousandPrice);
                
                $addPirceTo = "";
                for($j = $countOfMan ; $j < 4 ; $j++){
                    $addPirceTo .= "0";
                }
                $thousandPrice = $addPirceTo.$thousandPrice;
            }

            $price = $milionPrice.$thousandPrice."0000";

            if(!is_numeric($price)){
                $convartResult->is_convart = false;
                $price = null;
            }

            if($i == 0){
                $convartResult->afterConvartMinValue = $price == null ? null :$price ;
            }else{
                $convartResult->afterConvartMaxValue = $price == null ? null :$price ;
            }
        }
        return $convartResult;
    }

    protected function convartLocationArea($rawLandArea){

        $rawLandAreaArray = $this->splitData($rawLandArea);
        // create variable for retrun value..
        $convartResult = new ResultConvartRange();
        for($i = 0 ; $i < count($rawLandAreaArray); $i++){
            if($i == 2){
                break;
            }

            // patterrn 1
            //$LnadArea = str_replace(",","",$rawLandAreaArray[$i]);
            $LnadArea = $rawLandAreaArray[$i];
            $posDot = strpos($LnadArea,'m2');
            $LnadArea = mb_substr($LnadArea,0,$posDot);

            if(!is_numeric($LnadArea)){
                $convartResult->is_convart = false;
                $LnadArea = null;
            }

            if($i == 0){
                $convartResult->afterConvartMinValue = $LnadArea == null ? null :$LnadArea ;
            }else{
                $convartResult->afterConvartMaxValue =$LnadArea == null ? null :$LnadArea ;
            }
        }
        return $convartResult;
    }
}