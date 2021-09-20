<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class PropertyDeliveryTargetSetting extends Model
{
    protected $table = 'property_delivery_target_settings';
	public $timestamps = false;

    protected $fillable = [
		'property_deliveries_id',
		'companies_id',
		'company_users_id',
		'customers_id',
    ];
}
