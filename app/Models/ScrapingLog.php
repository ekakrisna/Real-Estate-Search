<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PropertyConvertStatus;

class ScrapingLog extends Model
{
    const NEW_REGISTER = 100;
    const UPDATE_PRICE = 210;
    const UPDATE_LOAND_AREA = 220;
    const NO_UPDATE = 300;
    const FINISH_PUBLISH = 400;
    const WRONG_PRICE = 500;
    const WRONG_LOAND_AREA = 510;
    const WRONG_LOCATION = 520;

    protected $fillable = [
        'scraping_id',
        'status',
        'is_adapt'
	];

    public function scraping()
	{
		return $this->belongsTo(Scraping::class, 'scraping_id');
	}
    
    public static function convertScrapingLog($scrapingLogStatus){
        $status = null;
        switch($scrapingLogStatus){
            case self::WRONG_LOCATION:
                $status = LpPropertyConvertStatus::WRONG_LOCATION;
                break;
            case self::WRONG_PRICE:
                $status = LpPropertyConvertStatus::WRONG_PRICE;
                break;
            case self::WRONG_LOAND_AREA:
                $status = LpPropertyConvertStatus::WRONG_LAND_AREA;
                break;
            default :
                $status = LpPropertyConvertStatus::SUCCESSFUL;
        }
        return $status;
    }

    public function setScrapingIdAttribute($value)
    {
        $this->attributes['scraping_id'] = $value;
    }

}
