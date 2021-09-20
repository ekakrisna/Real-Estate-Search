<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class CustomerSearchHistory
 *
 * @property int $id
 * @property int|null $customers_id
 * @property string|null $location
 * @property string|null $minimum_price
 * @property string|null $maximum_price
 * @property string|null $minimum_land_area
 * @property string|null $maximum_land_area
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Customer|null $customer
 *
 * @package App\Models
 */
class CustomerSearchHistory extends Model
{
    protected $table = 'customer_search_histories';
    public $timestamps = true;
    protected $appends = ['url', 'ja'];

    protected $casts = [
        'id' => 'int',
        'customers_id' => 'int',
        'minimum_price' => 'int',
        'maximum_price' => 'int',
        'minimum_land_area' => 'float',
        'maximum_land_area' => 'float'
    ];

    protected $fillable = [
        'customers_id',
        'location',
        'minimum_price',
        'maximum_price',
        'minimum_land_area',
        'maximum_land_area'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customers_id');
    }

    // ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute()
    {
        //$locale = App::getLocale();
        $result = new \stdClass;
        $format = "Y年m月d日 H:i";
        //if( 'en' === $locale ) $format = 'Y-m-d h:i';
        if (!empty($this->created_at)) $result->created_at = Carbon::parse($this->created_at)->format($format);
        if (!empty($this->updated_at)) $result->updated_at = Carbon::parse($this->updated_at)->format($format);
        return $result;
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // @TODO: Build URL based on route
    // ----------------------------------------------------------------------
    public function getUrlAttribute()
    {
        if (empty($this->id)) return null;
        $url = new \stdClass;
        $url->edit = '#';
        $url->view = '#';
        $url->change_flag = route('admin.changeCustomerFlag', $this->customers_id);
        return $url;
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

    public static function StoreCustomerSearchHistory($customers_id, $location = null, $filterMinPrice = null, $filterMaxPrice = null, $filterMinLandArea = null, $filterMaxLandArea = null)
    {
        try {
            //-----------------------------------------------------------------------------------
            //GET customer data first
            //-----------------------------------------------------------------------------------
            $customer = Customer::find($customers_id);
            //-----------------------------------------------------------------------------------

            //-----------------------------------------------------------------------------------
            // Not store history record if not_leave_record is flagged (that mean restriction to send my searching history: privacy setting)
            //-----------------------------------------------------------------------------------
            if (!$customer->not_leave_record) {

                $storeCustomerSearchHistory = CustomerSearchHistory::create([
                    'customers_id' =>  $customers_id,
                    'location'    => $location,
                    'minimum_price' => $filterMinPrice,
                    'maximum_price' => $filterMaxPrice,
                    'minimum_land_area' =>  $filterMinLandArea,
                    'maximum_land_area' =>  $filterMaxLandArea,
                ]);

                return $storeCustomerSearchHistory;
            }
            //-----------------------------------------------------------------------------------

        } catch (\Exception $e) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Failed Create CustomerSearchHistory: ", ['error' => $e->getMessage()]);
            sendMessageOfErrorReport("Failed Create CustomerSearchHistory Error: ", $e);
            //------------------------------------------------------
        }
    }
}
