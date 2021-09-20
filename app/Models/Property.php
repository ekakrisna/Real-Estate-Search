<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class Property
 *
 * @property int $id
 * @property int $property_statuses_id
 * @property int|null $property_publishing_settings_id
 * @property int|null $companies_id
 * @property string|null $location
 * @property bool|null $building_conditions
 * @property string|null $building_conditions_desc
 * @property int|null $minimum_land_area
 * @property int|null $maximum_land_area
 * @property int|null $minimum_price
 * @property int|null $maximum_price
 * @property string|null $contact_us
 * @property string|null $publication_destination
 * @property string|null $publication_destination_link
 * @property Carbon|null $publish_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property PropertyPublishingSetting|null $property_publishing_setting
 * @property PropertyStatus $property_status
 * @property Collection|CustomerContactU[] $customer_contact_us
 * @property Collection|CustomerFavoriteProperty[] $customer_favorite_properties
 * @property Collection|CustomerLogActivity[] $customer_log_activities
 * @property Collection|PropertyDelivery[] $property_deliveries
 * @property Collection|PropertyFlyer[] $property_flyers
 * @property Collection|PropertyLogActivity[] $property_log_activities
 * @property Collection|PropertyPhoto[] $property_photos
 *
 * @package App\Models
 */
class Property extends Model
{
    protected $table = 'properties';
    public $timestamps = true;

    protected $casts = [
        'id' => 'int',
        'property_statuses_id' => 'int',
        'property_publishing_settings_id' => 'int',
        'companies_id' => 'int',
        'building_conditions' => 'bool',
        'minimum_land_area' => 'float',
        'maximum_land_area' => 'float',
        'minimum_price' => 'int',
        'maximum_price' => 'int'
    ];

    protected $dates = [
        'publish_date'
    ];

    protected $fillable = [
        'property_statuses_id',
        'scraping_id',
        'companies_id',
        'location',
        'property_scraping_type_id',
        'building_conditions',
        'building_conditions_desc',
        'minimum_land_area',
        'maximum_land_area',
        'minimum_price',
        'maximum_price',
        'contact_us',
        'publish_date',
        'is_conversion',
        'town_id',
        'property_convert_status_id',
        'land_status',
        'is_reserve',
        'property_no',  // "property_no" is abolished!! please refer "property_publish.property_number"
        'is_bug_report'
    ];

    protected $appends = ['url', 'ja', 'label', 'favorited', 'last_photo_sort_number', 'last_flyer_sort_number'];
    protected $with = ['photos'];

    public function property_publishing_setting()
    {
        return $this->hasMany(PropertyPublishingSetting::class, 'properties_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'companies_id');
    }

    public function property_status()
    {
        return $this->belongsTo(PropertyStatus::class, 'property_statuses_id');
    }

    public function customer_contact_us()
    {
        return $this->hasMany(CustomerContactU::class, 'properties_id');
    }

    public function customer_favorite_properties()
    {
        return $this->hasMany(CustomerFavoriteProperty::class, 'properties_id');
    }

    public function favorites()
    {
        return $this->hasMany(CustomerFavoriteProperty::class, 'properties_id');
    }

    public function customer_log_activities()
    {
        return $this->hasMany(CustomerLogActivity::class, 'properties_id');
    }

    public function property_deliveries()
    {
        return $this->hasMany(PropertyDelivery::class, 'properties_id');
    }

    public function property_flyers()
    {
        return $this->hasMany(PropertyFlyer::class, 'properties_id');
    }

    public function property_log_activities()
    {
        return $this->hasMany(PropertyLogActivity::class, 'properties_id');
    }

    public function property_photos()
    {
        return $this->hasMany(PropertyPhoto::class, 'properties_id');
    }

    public function photos()
    {
        return $this->hasMany(PropertyPhoto::class, 'properties_id');
    }

    public function property_scraping()
    {
        return $this->belongsTo(Scraping::class, 'scraping_id');
    }

    public function property_publish()
    {
        return $this->hasMany(PropertyPublish::class, 'properties_id');
    }

    public function town()
    {
        return $this->hasOne(Town::class, 'town_id');
    }

    public function property_convert_status()
    {
        return $this->belongsTo(PropertyStatus::class, 'property_convert_status_id');
    }

    public function customer_news_property()
    {
        return $this->hasMany(CustomerNewsProperty::class, 'property_id');
    }

    public function before_login_customer_log_activities()
    {
        return $this->hasMany(BeforeLoginCustomerLoginLogActivities::class, 'properties_id');
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
    // Flag if this property is favorited by the Customer
    // ----------------------------------------------------------------------
    public function getFavoritedAttribute()
    {
        if (!empty(Auth::guard('user')->user())) {
            $propertyID = $this->id;
            $customerID = Auth::guard('user')->user()->id;
            $favorited = $this->favorites->first(function ($entry) use ($propertyID, $customerID) {
                $qualifiedProperty = $entry->properties_id === $propertyID;
                $qualifiedCustomer = $entry->customers_id === $customerID;
                return $qualifiedProperty && $qualifiedCustomer;
            });
            return !empty($favorited);
        }
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Last Sort Number
    // ----------------------------------------------------------------------
    public function getLastPhotoSortNumberAttribute()
    {
        return $this->property_photos()->max('sort_number');
    }
    public function getLastFlyerSortNumberAttribute()
    {
        return $this->property_flyers()->max('sort_number');
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Build URL based on route
    // ----------------------------------------------------------------------
    public function getUrlAttribute()
    {
        if (empty($this->id)) return null;
        $url = new \stdClass;
        $url->edit = route('admin.property.edit',  $this->id);
        $url->view = route('admin.property.detail', $this->id);
        $url->manage_view = route('manage.property.show', $this->id);
        $url->manage_edit = route('manage.property.edit',  $this->id);
        $url->frontend_view = route('frontend.property.detail',  $this->id);
        $url->manage_search = route('manage.property.show',  $this->id);
        $url->approval = route('admin.approval.property',  $this->id);
        $url->upload_property_photo = route('admin.property.upload_photo',  $this->id);
        $url->delete_property_photo = route('admin.property.delete_photo',  $this->id);
        $url->order_property_photo = route('admin.property.order_photo',  $this->id);
        $url->upload_property_flyer = route('admin.property.upload_flyer',  $this->id);
        $url->delete_property_flyer = route('admin.property.delete_flyer',  $this->id);
        $url->order_property_flyer = route('admin.property.order_flyer',  $this->id);

        $url->order_images = route('admin.property.order_images', $this->id);

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
        if (!empty($this->updated_at)) $result->updated_date = Carbon::parse($this->updated_at)->format($date_format);
        if (!empty($this->publish_date)) $result->publish_date = Carbon::parse($this->publish_date)->format($date_format);
        if (!empty($this->publish_date)) $result->publish_time = Carbon::parse($this->publish_date)->format($format);
        if (!empty($this->created_at)) {
            $result->lessMonth = Carbon::now() < date('Y-m-d H:m:i', strtotime('+30 days', strtotime($this->created_at)));
        }
        if (!empty($this->created_at) && !empty($this->updated_at)) {
            $lessMonth = Carbon::now() < date('Y-m-d H:m:i', strtotime('+30 days', strtotime($this->updated_at)));
            if ($this->created_at != $this->updated_at && $lessMonth == true) {
                $result->withUpdate = true;
            } else {
                $result->withUpdate =  false;
            }
        }
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

    public function scopeOtherThanBackUp($query)
    {
        return $query->where('property_convert_status_id', '<>', PropertyConvertStatus::BACKUP_INVALID_DATA);
    }

    public function getConvertStatusIdAttribute()
    {
        return $this->property_convert_status_id;
    }

    public function getPropertyStatusIdAttribute()
    {
        return $this->property_statuses_id;
    }

    public function setScrapingIdAttribute($value)
    {
        $this->attributes['scraping_id'] = $value;
    }

    public function setScrapingTypeIdAttribute($value)
    {
        $this->attributes['property_scraping_type_id'] = $value;
    }

    public function setPropertyStatusIdAttribute($value)
    {
        $this->attributes['property_statuses_id'] = $value;
    }

    public function setConvertStatusIdAttribute($value)
    {
        $this->attributes['property_convert_status_id'] = $value;
    }

    public function fillDataFromScrapingLog($scrapingLog)
    {
        $is_reserve = false;
        if (ScrapingLog::NEW_REGISTER != $scrapingLog->status) {
            $is_reserve = $scrapingLog->scraping->property->is_reserve;
        }

        $this->attributes['is_reserve']  = $is_reserve;
        $this->attributes['companies_id']  = 1;
    }
}
