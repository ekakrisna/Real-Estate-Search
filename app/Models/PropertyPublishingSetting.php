<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PropertyPublishingSetting
 * Configuration for PropertyStatus::PULICATION_LIMITED (掲載(限定))
 * @property int $id
 * @property int|null $property_deliveries_id
 * @property int|null $companies_id
 * @property int|null $company_users_id
 *
 * @property Collection|Property[] $properties
 *
 * @package App\Models
 */
class PropertyPublishingSetting extends Model
{
	protected $table = 'property_publishing_settings';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'customers_id ' => 'int',
		'companies_id' => 'int',
		'company_users_id' => 'int',
		'properties_id' => 'int'
	];

	protected $fillable = [
		'customers_id',
		'companies_id',
		'company_users_id',
		'properties_id',
		'type'
	];

	public function properties()
	{
		return $this->belongsTo(Property::class, 'properties_id');
	}

	public function company()
	{
		return $this->belongsTo(Company::class, 'companies_id');
	}

	public function company_user()
	{
		return $this->belongsTo(CompanyUser::class, 'company_users_id');
	}

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'customers_id');
	}
}
