<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ContactUsType
 * 
 * @property int $id
 * @property string|null $label
 * 
 * @property Collection|CustomerContactU[] $customer_contact_us
 *
 * @package App\Models
 */
class ContactUsType extends Model
{
    const HOW_TO = 1;
	const FORGOT_EMAIL = 101;
	const PROPERTY_INQUIRY = 901;

	protected $table = 'contact_us_types';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'label'
	];

	public function customer_contact_us()
	{
		return $this->hasMany(CustomerContactU::class, 'contact_us_types_id');
	}
}
