<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScrapingPublish extends Model
{
    protected $table = 'scraping_publish';
    public $timestamps = false;

    protected $fillable = [
        'scraping_id',
        'publication_destination',
        'url',
        'day_of_week',
        'property_number'
	];

    public function scraping()
	{
		return $this->belongsTo(Scraping::class, 'scraping_id');
	}

    public function scopeScrapingId($query,$id){
        return $query->where('scraping_id', $id);
    }

    public function setScrapingIdAttribute($value)
    {
        $this->attributes['scraping_id'] = $value;
    }
}
