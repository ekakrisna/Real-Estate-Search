<?php

namespace App\Console\Scraping\DataRow;

use App\Console\Scraping\DataRow\Base\BaseScrapingRow;
use App\Console\Scraping\DataRow\ResultClass\ResultConvartRange;
use App\Console\Scraping\DataRow\ResultClass\AfterSplitRawData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\LpPropertyCotegory;

class LpReinsDataRow extends BaseScrapingRow
{  
    public $building_area =null;
    public $building_age = null;
    public $house_layout = true;
    public $connecting_road =null;
    public $contracted_years = null;
    public $lp_property_category_id = null;
    
    protected function getAfterSplitRawData($rawRowData){
        $splitResult = new AfterSplitRawData();
        $splitResult->price = $rawRowData[0];
        $splitResult->landArea =$rawRowData[1];
        $splitResult->location = $rawRowData[2];
        $splitResult->property_number = $rawRowData[4];
        $splitResult->building_area =$rawRowData[5];
        $splitResult->house_layout = $rawRowData[6];
        $splitResult->connecting_road =$rawRowData[7];
        $splitResult->contracted_years = $rawRowData[8];
        $splitResult->building_age =$rawRowData[9];
        $splitResult->property_category = $rawRowData[10];
        $splitResult->publication_destination ="REINS";

        if($splitResult->price === "" && $splitResult->landArea === "" && $splitResult->location === "" ){
            $splitResult->isCorrectlyGet = false;
        } 
        return $splitResult;
    }

    protected function setProperty($afterSplitRawData){
        $proprety_area[] = $afterSplitRawData->building_area;
        $building_age_result = $this->convartLocationArea($proprety_area);

        $this->building_area =$building_age_result->afterConvartMinValue;
        $this->contracted_years =$this->toSeireki($afterSplitRawData->contracted_years);
       
        $building_year = $this->toDate($afterSplitRawData->building_age);
        $building_month = 0;
        if($building_year != null && $this->contracted_years != null){
            $building_month = $this->contracted_years->diffInYears($building_year);
        }
        
        $this->building_age = $building_month;
        $this->house_layout = $afterSplitRawData->house_layout;
        $this->connecting_road =$afterSplitRawData->connecting_road;
        $this->lp_property_category_id = LpPropertyCotegory::getCategoryIdFromName($afterSplitRawData->property_category);

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
        // create variable for retrun value.
        $convartResult = new ResultConvartRange();
      
        $LnadArea = str_replace(",","",$rawLandArea);

        $type = gettype($LnadArea);
        if(gettype($LnadArea) =="array"){
            $LnadArea = $LnadArea[0];
        }
        $posDot = strpos($LnadArea,'㎡');
        $LnadArea = mb_substr($LnadArea,0,$posDot);

        if(!is_numeric($LnadArea)){
            $convartResult->is_convart = false;
            $LnadArea = null;
        }
        $convartResult->afterConvartMinValue = $LnadArea == null ? null :$LnadArea ;
        return $convartResult;
    }

    private function toSeireki($wareki_year) {
        $wareki_year = str_replace('元年', '1年', mb_convert_kana($wareki_year, 'n'));
        if(preg_match('!^(明治|大正|昭和|平成|令和)([0-9]+)年([0-9]+)月(\S*[0-9]+)日!', $wareki_year, $matches)) {
    
            $era_name = $matches[1];
            $year = intval($matches[2]);
            $month = intval($matches[3]);
            if($month == 0){
                $month = intval(substr($matches[3],-1));
            }

            $day = intval($matches[4]);
            if($day == 0){
                $day = intval(substr($matches[4],-1));
            }
    
            // convert year
            if($era_name === '明治') {
                $year += 1867;
    
            } else if($era_name === '大正') {
                $year += 1911;
    
            } else if($era_name === '昭和') {
                $year += 1925;
    
            } else if($era_name === '平成') {
                $year += 1988;
    
            } else if($era_name === '令和') {
                $year += 2018;
            }
            
            $date = $year."-".$month."-".$day;
            return new Carbon($date);
        }
        return null;
    }

    private function toDate($wareki_year) {
        $year = null;
        $month = null;
        $wareki_year = str_replace('元年', '1年', mb_convert_kana($wareki_year, 'n'));
        
        if(preg_match('!^([0-9]+)年*!', $wareki_year, $matches)) {
            $year = intval($matches[1]);
        }

        if(preg_match('!([0-9]+)月$!', $wareki_year, $matches)) {
            $month = intval($matches[1]);
        }

        if($year == null || $month == null){return null;}
        
        $date = $year."-".$month;
        return new Carbon($date);
    }
}