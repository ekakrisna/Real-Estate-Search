<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Log;

/**
 * Class CustomerLogActivity
 *
 * @property int $id
 * @property int|null $customers_id
 * @property int|null $properties_id
 * @property int $action_types_id
 * @property string $ip
 * @property Carbon $access_time
 *
 * @property ActionType $action_type
 * @property Property|null $property
 * @property Customer|null $customer
 *
 * @package App\Models
 */
class CustomerLogActivity extends Model
{
	use \Awobaz\Compoships\Compoships;

	const SIGN_UP = 1;
	const LOGIN = 2;
	const CHANGE_MY_SETTING = 3;
	const PROPERTY_BROWSING = 4;
	const PROPERTY_FAVORITES = 5;
	const CONTACT_US = 6;
	const SUSPENSION_OF_USE = 7;
	const RESET_PASSWORD = 8;

	protected $table = 'customer_log_activities';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'customers_id' => 'int',
		'properties_id' => 'int',
		'action_types_id' => 'int'
	];

	protected $dates = [
		'access_time'
	];

	protected $fillable = [
		'customers_id',
		'properties_id',
		'action_types_id',
		'ip',
		'access_time'
	];

	public function action_type()
	{
		return $this->belongsTo(ActionType::class, 'action_types_id');
	}

	public function property()
	{
		return $this->belongsTo(Property::class, 'properties_id');
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'customers_id');
	}

	public function customer_favorite_property()
	{
		return $this->hasMany(CustomerFavoriteProperty::class, ['customers_id', 'properties_id'], ['customers_id', 'properties_id']);
	}

	protected $appends = ['ja', 'url'];

	// ----------------------------------------------------------------------
	// Get Japanese formatted timestamps
	// ----------------------------------------------------------------------
	public function getJaAttribute()
	{
		$result = new \stdClass;
		$format = "Y年m月d日 H:i";
		$date_format = "Y年m月d日";
		if (!empty($this->access_time)) $result->access_time = Carbon::parse($this->access_time)->format($format);
		if (!empty($this->access_time)) $result->access_date = Carbon::parse($this->access_time)->format($date_format);
		return $result;
	}
	// ----------------------------------------------------------------------

	public function getUrlAttribute()
	{
		if (empty($this->id)) return null;
		if (empty($this->properties_id)) return null;
		$url = new \stdClass;
		$url->edit = '#';
		$url->view = '#';
		$url->view_property = route('admin.property.detail', $this->properties_id);
		return $url;
	}

	// ----------------------------------------------------------------------
	// @TODO: Build Common storing process of Customer Log Activity
	// ----------------------------------------------------------------------
	public static function storeCustomerLog($actionType, $customers_id, $ip_address, $properties_id = null)
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
				$storeCustomerLog = CustomerLogActivity::create([
					'customers_id' =>  $customers_id,
					'properties_id'    => $properties_id,
					'action_types_id' => $actionType,
					'ip' => $ip_address,
					'access_time' =>  Carbon::now()
				]);
				return $storeCustomerLog;
			}
		} catch (\Exception $e) {
			//------------------------------------------------------
			//Send chat to chatwork if failing in function
			//------------------------------------------------------
			Log::info(Carbon::now() . " - Failed Create Customer Log Activity (models/CustomerLogActivity) storeCustomerLog: ", ['error' => $e->getMessage()]);
			sendMessageOfErrorReport("Failed Create Customer Log Activity (models/CustomerLogActivity) storeCustomerLog, Error: ", $e);
			//------------------------------------------------------
		}
	}

	// ----------------------------------------------------------------------
}
