<?php

namespace App\Console\Scraping\DataRow\ResultClass;

/**
 * Scraping of result Data
 */
class AfterSplitRawData
{
    public $location = "";
    public $price = "";
    public $landArea = "";
    public $land_status = "";
    public $traffic = "";
    public $url = "";
    public $publication_destination = "";
    public $property_no = null;
    public $building_area = null;
    public $building_age = null;
    public $property_number = null; // TODO: Explain!
    public $house_layout = true;
    public $connecting_road = null;
    public $contracted_years = null;
    public $isCorrectlyGet = true; // if all data can not get , this flag change to false. this variable will use to get error of if it will be changed layout website that scraping destination.
}
