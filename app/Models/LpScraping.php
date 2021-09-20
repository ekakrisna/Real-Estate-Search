<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpScraping extends Model
{
    protected $table = 'lp_scrapings';
    public $timestamps = true;

    protected $casts = [
        'minimum_land_area' => 'float',
        'maximum_land_area' => 'float',
        'minimum_price' => 'int',
        'maximum_price' => 'int'
    ];
    protected $fillable = [
        'minimum_price',
        'maximum_price',
        'minimum_land_area',
        'maximum_land_area',
        'building_area',
        'building_age',
        'location',
        'house_layout',
        'connecting_road',
        'contracted_years',
        'lp_property_category_id'
    ];

    public function property()
    {
        return $this->hasOne(LpProperty::class,'lp_scrapings_id');
    }

    public function scraping_log()
    {
        return $this->hasMany(LpScrapingLog::class, 'lp_scraping_id');
    }

    public function scraping_publish()
    {
        return $this->hasMany(LpScrapingPublish::class, 'lp_scraping_id');
    }
}
