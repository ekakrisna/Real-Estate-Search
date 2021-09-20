<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PropertyDelivery
 * 
 * @property int $id
 * @property int|null $properties_id
 * @property string|null $subject
 * @property string|null $text
 * @property bool|null $favorite_registered_area
 * @property bool|null $exclude_received_over_three
 * @property bool|null $exclude_customers_outside_the_budget
 * @property bool|null $exclude_customers_outside_the_desired_land_area
 * @property Carbon|null $created_at
 * @property Carbon|null $update_at
 * 
 * @property Property|null $property
 * @property Collection|CustomerDeliveriesInfo[] $customer_deliveries_infos
 *
 * @package App\Models
 */
class PropertyDelivery extends Model
{
	protected $table = 'property_deliveries';
	public $timestamps = true;

	protected $casts = [
		'id' => 'int',
		'properties_id' => 'int',
		'favorite_registered_area' => 'bool',
		'exclude_received_over_three' => 'bool',
		'exclude_customers_outside_the_budget' => 'bool',
		'exclude_customers_outside_the_desired_land_area' => 'bool'
	];

	protected $dates = [
		'updated_at'
	];

	protected $fillable = [
		'properties_id',
		'subject',
		'text',
		'favorite_registered_area',
		'exclude_received_over_three',
		'exclude_customers_outside_the_budget',
		'exclude_customers_outside_the_desired_land_area',
		'created_at',
		'updated_at'
	];

	public function property()
	{
		return $this->belongsTo(Property::class, 'properties_id');
	}

	public function customer_deliveries_infos()
	{
		return $this->hasMany(CustomerDeliveriesInfo::class, 'property_deliveries_id');
	}

	public function property_deliveries()
	{
		return $this->hasMany(PropertyDeliveryTargetSetting::class, 'property_deliveries_id');
	}

	public function customer_news()
	{
		return $this->hasMany(CustomerNew::class, 'property_deliveries_id');
	}

	protected $appends = ['ja'];

	// ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute(){
        $result = new \stdClass; $format = "Y-m-d";
        if( !empty( $this->created_at )) $result->created_at = Carbon::parse( $this->created_at )->format( $format );
        if( !empty( $this->updated_at )) $result->updated_at = Carbon::parse( $this->updated_at )->format( $format );
        return $result;
    }
}
