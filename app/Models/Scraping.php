<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Scraping extends Model
{
    protected $table = 'scrapings';
    public $timestamps = true;

    protected $fillable = [
        'minimum_price',
        'maximum_price',
        'minimum_land_area',
        'maximum_land_area',
        'location',
        'traffic',
        'town_id',
        'property_no', // "property_no" is abolished!! please refer "scraping_publish.property_number"
    ];

    protected $casts = [
        'minimum_land_area' => 'float',
        'maximum_land_area' => 'float',
        'minimum_price' => 'int',
        'maximum_price' => 'int'
    ];


    public function property()
    {
        return $this->hasOne(Property::class, 'scraping_id');
    }

    public function scraping_log()
    {
        return $this->hasMany(ScrapingLog::class, 'scraping_id');
    }

    public function scraping_publish()
    {
        return $this->hasMany(ScrapingPublish::class, 'scraping_id');
    }

    public function scraping_land_status()
    {
        return $this->belongsTo(ScrapingLandStatus::class, 'scraping_id');
    }
}
