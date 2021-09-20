<?php

namespace App\Models;

use App\Model\LpProperty;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LpPropertyLogActivity extends Model
{
    protected $table = 'lp_property_log_activities';
    public $timestamps = false;

    protected $casts = [
        'id' => 'int',
        'lp_properties_id' => 'int',
        'lp_property_scraping_types_id' => 'int'
    ];

    protected $fillable = [
        'lp_properties_id',
        'before_update_text',
        'after_update_text',
        'lp_property_scraping_types_id',
        'created_at'
    ];

    public function property()
    {
        return $this->belongsTo(LpProperty::class, 'lp_properties_id');
    }

    public function property_scraping_type()
    {
        return $this->belongsTo(LpPropertyScrapingType::class, 'lp_property_scraping_types_id');
    }

    protected $appends = ['ja'];

    // ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute()
    {
        $result = new \stdClass;
        $format = "Y年m月d日";
        $format_time = "Y年m月d日 H:i";
        if (!empty($this->created_at)) $result->created_at = Carbon::parse($this->created_at)->format($format);
        if (!empty($this->created_at)) $result->created_time = Carbon::parse($this->created_at)->format($format_time);
        return $result;
    }
    // ----------------------------------------------------------------------

    public function setPropertiesIdAttribute($value)
    {
        $this->attributes['lp_properties_id'] = $value;
    }

    public function setPropertyScrapingTypeIdAttribute($value)
    {
        $this->attributes['lp_property_scraping_types_id'] = $value;
    }
	
}
