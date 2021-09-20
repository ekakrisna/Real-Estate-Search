<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\prefecture;
use App\Models\City;
use App\Models\Town;
use App\Models\CustomerDesiredArea as DesiredArea;


/**
 * Class Customer
 *
 * @property int $id
 * @property int|null $company_users_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property bool|null $flag
 * @property bool|null $is_cancellation
 * @property bool|null $not_leave_record
 * @property int|null $minimum_price_id
 * @property int|null $maximum_price_id
 * @property int|null $minimum_price_land_area_id
 * @property int|null $maximum_price_land_area_id
 * @property int|null $minimum_land_area_id
 * @property int|null $maximum_land_area_id
 * @property bool|null $license
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|CustomerContactU[] $customer_contact_us
 * @property Collection|CustomerDeliveriesInfo[] $customer_deliveries_infos
 * @property Collection|CustomerDesiredArea[] $customer_desired_areas
 * @property Collection|CustomerFavoriteProperty[] $customer_favorite_properties
 * @property Collection|CustomerLogActivity[] $customer_log_activities
 * @property Collection|CustomerSearchHistory[] $customer_search_histories
 *
 * @package App\Models
 */
class Customer extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    public $timestamps = true;

    protected $table = 'customers';

    protected $casts = [
        'id' => 'int',
        'company_users_id' => 'int',
        'flag' => 'bool',
        'is_cancellation' => 'bool',
        'not_leave_record' => 'bool',
        'minimum_price' => 'int',
        'maximum_price' => 'int',
        'minimum_price_land_area' => 'int',
        'maximum_price_land_area' => 'int',
        'minimum_land_area' => 'float',
        'maximum_land_area' => 'float',
        'license' => 'bool'

    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $fillable = [
        'company_users_id',
        'name',
        'email',
        'password',
        'phone',
        'flag',
        'is_cancellation',
        'not_leave_record',
        'minimum_price',
        'maximum_price',
        'minimum_price_land_area',
        'maximum_price_land_area',
        'minimum_land_area',
        'maximum_land_area',
        'license'
    ];

    public function company_user()
    {
        return $this->belongsTo(CompanyUser::class, 'company_users_id');
    }

    public function customer_contact_us()
    {
        return $this->hasMany(CustomerContactUs::class, 'customers_id');
    }

    public function customer_deliveries_infos()
    {
        return $this->hasMany(CustomerDeliveriesInfo::class, 'customers_id');
    }

    public function customer_desired_areas()
    {
        return $this->hasMany(CustomerDesiredArea::class, 'customers_id');
    }

    public function desiredAreas()
    {
        return $this->hasMany(CustomerDesiredArea::class, 'customers_id');
    }

    public function customer_favorite_properties()
    {
        return $this->hasMany(CustomerFavoriteProperty::class, 'customers_id');
    }

    public function customer_log_activities()
    {
        return $this->hasMany(CustomerLogActivity::class, 'customers_id');
    }

    public function customer_search_histories()
    {
        return $this->hasMany(CustomerSearchHistory::class, 'customers_id');
    }

    // get customer action type where checked property 2 weeks past
    public function properties_checked()
    {
        return $this->hasMany(CustomerLogActivity::class, 'customers_id')->where('action_types_id', '=', 4)->where('access_time', '>=', Carbon::now()->subDays(14)->toDateTimeString());
    }

    // get customer fav properties
    public function favorite_properties()
    {
        return $this->hasMany(CustomerFavoriteProperty::class, 'customers_id');
    }

    public function customer_news()
    {
        return $this->hasMany(CustomerNew::class, 'customers_id');
    }

    public function customer_last_activity()
    {
        return $this->hasOne(CustomerLogActivity::class, 'customers_id')->orderBy('access_time', 'desc');
    }

    protected $appends = ['url', 'ja'];

    // ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute()
    {
        $result = new \stdClass;
        $format = "Y年m月d日 H:i";
        if (!empty($this->created_at)) $result->created_at = Carbon::parse($this->created_at)->format($format);
        if (!empty($this->updated_at)) $result->updated_at = Carbon::parse($this->updated_at)->format($format);
        return $result;
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Build URL based on route
    // ----------------------------------------------------------------------
    public function getUrlAttribute()
    {
        if (empty($this->id)) return null;
        $url = new \stdClass;

        $url->edit = route('admin.customer.edit',  $this->id);
        $url->view = route('admin.customer.detail', $this->id);
        $url->flag = route('admin.customer.flag', $this->id);

        $url->manage_edit = route('manage.customer.edit',  $this->id);
        $url->manage_view = route('manage.customer.detail', $this->id);
        $url->manage_flag = route('manage.customer.flag', $this->id);
        $url->change_flag = route('admin.changeCustomerFlag', $this->id);

        return $url;
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Function for Social Media Accounts
    // ----------------------------------------------------------------------
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class, 'customers_id');
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Find if the Customer has desired-area by the location
    // ----------------------------------------------------------------------
    public static function hasDesiredLocation($location)
    {
        // ------------------------------------------------------------------
        $customer = Auth::guard('user')->user();
        if (empty($customer)) return;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        try {
            // --------------------------------------------------------------
            // Parse the location
            // Location sample: '宮城県仙台市青葉区大町一丁目'
            // --------------------------------------------------------------
            $parsedLocation = parseLocation($location);
            // --------------------------------------------------------------

            $prefectureName = $parsedLocation['prefecture'];
            $cityName = $parsedLocation['city'];
            $townName = $parsedLocation['town'];
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get the returned town
            // --------------------------------------------------------------
            $prefecture = prefecture::where('name', $prefectureName)->first();
            $city = City::where('name', $cityName)->where('prefectures_id', $prefecture->id)->first();
            $town =  Town::with('city.prefecture')->where('name', $townName)->where('cities_id', $city->id)->first();
            // --------------------------------------------------------------

            // --------------------------------------------------------------

            $result = DesiredArea::where('cities_id', $city->id)->where('customers_id', $customer->id)->where(function ($query) use ($town) {
                $query->whereNull('cities_area_id')->orWhere('cities_area_id', $town->cities_area_id);
            })->exists();

            return $result;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
        } catch (\Exception $error) {
            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Models/Customer (hasDesiredLocation Function), Error: " . $error->getMessage());
            sendMessageOfErrorReport("Models/Customer (hasDesiredLocation Function), Error: ", $e);
            //------------------------------------------------------

            return null;
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------


    // ----------------------------------------------------------------------
    // Set or remove a specific location from the Customer's desired-areas
    // ----------------------------------------------------------------------
    public static function toggleDesiredLocation($location)
    {
        // ------------------------------------------------------------------
        $customer = Auth::guard('user')->user();
        if (empty($customer)) return;
        // ------------------------------------------------------------------

        // ------------------------------------------------------------------
        try {
            // --------------------------------------------------------------
            // Parse the location
            // Location sample: '宮城県仙台市青葉区大町一丁目'
            // --------------------------------------------------------------
            $parsedLocation = parseLocation($location);
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // Get the returned location properties
            // --------------------------------------------------------------

            $town = $parsedLocation->get('town');
            $town = Town::with('city.prefecture')->where('name', $town)->first();
            $city = $town->city;
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            $conditions = [
                'cities_area_id' => $town->cities_area_id,
                'cities_id' => $city->id,
                'customers_id' => $customer->id
            ];
            // --------------------------------------------------------------
            $desiredArea = DesiredArea::where($conditions)->first();
            // --------------------------------------------------------------

            // --------------------------------------------------------------
            // IF desired area exists, delete it
            // --------------------------------------------------------------
            if ($desiredArea) {
                $desiredArea->delete();
                return false;
            }
            // --------------------------------------------------------------
            // Otherwise, create one
            // --------------------------------------------------------------
            else {
                $entry = new DesiredArea();
                $entry->fill($conditions)->save();
                return true;
            }
            // --------------------------------------------------------------

            // --------------------------------------------------------------
        } catch (\Exception $error) {

            //------------------------------------------------------
            //Send chat to chatwork if failing in function
            //------------------------------------------------------
            Log::info(Carbon::now() . " - Models/Customer (toggleDesiredLocation Function), Error: " . $error->getMessage());
            sendMessageOfErrorReport("Models/Customer (toggleDesiredLocation Function), Error: ", $e);
            //------------------------------------------------------

            return $error;
        }
        // ------------------------------------------------------------------
    }
    // ----------------------------------------------------------------------
}
