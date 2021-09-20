<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LpProperty extends Model
{
    protected $table = 'lp_properties';
    public $timestamps = true;

    protected $casts = [
        'id' => 'int',
        'lp_scrapings_id' => 'int',
        'lp_property_scraping_type_id' => 'int',
        'lp_property_status_id' => 'int',
        'lp_property_convert_status_id' => 'int',
        'building_age' => 'int',
        'building_conditions' => 'bool',
        'minimum_land_area' => 'float',
        'maximum_land_area' => 'float',
        'building_area' => 'float',
        'minimum_price' => 'int',
        'maximum_price' => 'int'
    ];

    protected $dates = [
        'publish_date'
    ];

    protected $fillable = [
        'lp_scrapings_id',
        'scraping_id',
        'lp_property_scraping_type_id',
        'lp_property_status_id',
        'lp_property_convert_status_id',
        'location',
        'scraping_type_id',
        'minimum_land_area',
        'maximum_land_area',
        'minimum_price',
        'maximum_price',
        'building_area',
        'building_age',
        'house_layout',
        'connecting_road',
        'property_convert_status_id',
        'contracted_years',
        'publish_date',
        'property_no', // "property_no" is abolished!! please refer "lp_property_publish.property_number"
        'traffic',
        'lp_property_category_id'
    ];

    protected $appends = ['url', 'ja', 'label'];

    public function property_status()
    {
        return $this->belongsTo(LpPropertyStatus::class, 'lp_property_status_id');
    }

    public function property_scraping_type()
    {
        return $this->belongsTo(LpPropertyScrapingType::class, 'lp_property_scraping_type_id');
    }

    public function property_scraping()
    {
        return $this->hasMany(LpScraping::class, 'lp_properties_id');
    }

    public function property_convert_status()
    {
        return $this->belongsTo(LpPropertyConvertStatus::class, 'lp_property_convert_status_id');
    }

    public function property_publish()
    {
        return $this->hasMany(LpPropertyPublish::class, 'lp_properties_id');
    }

    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Get property labels
    // ----------------------------------------------------------------------
    public function getLabelAttribute()
    {
        // ------------------------------------------------------------------
        $threshold = 30; // 30 days
        $label = new \stdClass;
        // ------------------------------------------------------------------
        $today = Carbon::now();
        $createdAt = Carbon::parse($this->created_at);
        $updatedAt = Carbon::parse($this->updated_at);
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // New property
        // ------------------------------------------------------------------
        $recentlyAdded = $today->diffInDays($createdAt) <= $threshold;
        if ($recentlyAdded) $label->new = true;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Recently updated property
        // ------------------------------------------------------------------
        $updated = $createdAt->notEqualTo($updatedAt);
        $recentlyUpdated = $today->diffInDays($updatedAt) <= $threshold;
        if ($updated && $recentlyUpdated) $label->updated = true;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Property without building-conditions
        // ------------------------------------------------------------------
        $condition = $this->building_conditions;
        if (!$condition) $label->noCondition = true;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        // Property is_reserve
        // ------------------------------------------------------------------
        $is_reserve = $this->is_reserve;
        if ($is_reserve) $label->isReserve = true;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        return $label;
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Build URL based on route
    // ----------------------------------------------------------------------
    public function getUrlAttribute()
    {
        if (empty($this->id)) return null;
        $url = new \stdClass;
        $url->edit = route('admin.lp.property.edit',  $this->id);
        $url->editApproval = route('admin.lp.approval.property',  $this->id);
        return $url;
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute()
    {
        $result = new \stdClass;
        $format = "Y年m月d日 H:i";
        $date_format = "Y年m月d日";
        if (!empty($this->created_at)) $result->created_at = Carbon::parse($this->created_at)->format($date_format);
        if (!empty($this->updated_at)) $result->updated_at = Carbon::parse($this->updated_at)->format($format);
        if (!empty($this->contracted_years)) $result->contracted_years = Carbon::parse($this->contracted_years)->format($date_format);
        if (!empty($this->publish_date)) $result->publish_date = Carbon::parse($this->publish_date)->format($date_format);
        if (!empty($this->publish_date)) $result->publish_time = Carbon::parse($this->publish_date)->format($format);
        return $result;
    }
    // ----------------------------------------------------------------------

    public function scopePriceRange($query, $min, $max)
    {
        // ------------------------------------------------------------------
        // Minimum property price
        // ------------------------------------------------------------------
        if (!empty($min)) {
            $priceMin = fromMan($min);
            $query->where(function ($query) use ($priceMin) {
                $query->where('minimum_price', '>=', $priceMin)->orwhere('maximum_price', '>=', $priceMin);
            });
        }

        // ------------------------------------------------------------------
        // Maximum property price
        // ------------------------------------------------------------------
        if (!empty($max)) {
            $priceMax = fromMan($max);
            $query->where(function ($query) use ($priceMax) {
                $query->where('maximum_price', '<=', $priceMax)->orwhere('minimum_price', '<=', $priceMax);
            });
        }

        // ------------------------------------------------------------------
        // Between property price
        // ------------------------------------------------------------------
        if (!empty($min) && !empty($max)) {
            $min = fromMan($min);
            $max = fromMan($max);

            if ($min > $max) {
                $query->where('id', "-1");
                return $query;
            }

            $query->where(function ($query) use ($min, $max) {
                $query->where(function ($query) use ($min) {
                    $query->Where('minimum_price', '>=', $min)
                        ->orWhere('maximum_price', '>=', $min);
                })
                    ->where(function ($query) use ($max) {
                        $query->Where('minimum_price', '<=', $max)
                            ->orWhere('maximum_price', '<=', $max);
                    });
            });
        }
        return $query;
    }

    public function scopeLandAreaRange($query, $min, $max)
    {
        // ------------------------------------------------------------------
        // Minimum property land_area
        // ------------------------------------------------------------------
        if (!empty($min)) {
            $landAreaMin = fromTsubo($min);
            $query->where(function ($query) use ($landAreaMin) {
                $query->where('minimum_land_area', '>=', $landAreaMin)->orwhere('maximum_land_area', '>=', $landAreaMin);
            });
        }

        // ------------------------------------------------------------------
        // Maximum property land_area
        // ------------------------------------------------------------------
        if (!empty($max)) {
            $landAreaMax = fromTsubo($max);
            $query->where(function ($query) use ($landAreaMax) {
                $query->where('maximum_land_area', '<=', $landAreaMax)->orwhere('minimum_land_area', '<=', $landAreaMax);
            });
        }

        // ------------------------------------------------------------------
        // Between property land_area
        // ------------------------------------------------------------------
        if (!empty($min) && !empty($max)) {
            $min = fromTsubo($min);
            $max = fromTsubo($max);

            if ($min > $max) {
                $query->where('id', "-1");
                return $query;
            }

            $query->where(function ($query) use ($min, $max) {
                $query->where(function ($query) use ($min) {
                    $query->Where('minimum_land_area', '>=', $min)
                        ->orWhere('maximum_land_area', '>=', $min);
                })
                    ->where(function ($query) use ($max) {
                        $query->Where('minimum_land_area', '<=', $max)
                            ->orWhere('maximum_land_area', '<=', $max);
                    });
            });
        }
        return $query;
    }

    public function getConvertStatusIdAttribute()
    {
        return $this->lp_property_convert_status_id;
    }

    public function getPropertyStatusIdAttribute()
    {
        return $this->lp_property_status_id;
    }

    public function setScrapingIdAttribute($value)
    {
        $this->attributes['lp_scrapings_id'] = $value;
    }

    public function setScrapingTypeIdAttribute($value)
    {
        $this->attributes['lp_property_scraping_type_id'] = $value;
    }

    public function setPropertyStatusIdAttribute($value)
    {
        $this->attributes['lp_property_status_id'] = $value;
    }

    public function setConvertStatusIdAttribute($value)
    {
        $this->attributes['lp_property_convert_status_id'] = $value;
    }

    public function fillDataFromScrapingLog($scrapingLog)
    {
    }
}
