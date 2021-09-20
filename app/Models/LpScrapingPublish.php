<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpScrapingPublish extends Model
{
    protected $table = 'lp_scraping_publish';
    public $timestamps = false;

    protected $fillable = [
        'lp_scraping_id',
        'publication_destination',
        'url',
        'property_number',
        'day_of_week'
	];

    public function scraping()
	{
		return $this->belongsTo(LpScraping::class, 'lp_scraping_id');
	}

    public function scopeScrapingId($query,$id){
        return $query->where('lp_scraping_id', $id);
    }

    public function setScrapingIdAttribute($value)
    {
        $this->attributes['lp_scraping_id'] = $value;
    }
}
