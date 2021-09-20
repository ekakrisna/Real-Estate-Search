<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActionType
 *
 * @property int $id
 * @property string|null $label
 *
 * @property Collection|CustomerLogActivity[] $customer_log_activities
 *
 * @package App\Models
 */
class ActionType extends Model
{

    const SIGN_UP = 1;
	const LOGIN = 2;
	const CHANGE_MY_SETTING = 3;
	const PROPERTY_BROWSING = 4;
	const PROPERTY_FAVORITES = 5;
	const CONTACT_US = 6;
	const SUSPENSION_OF_USE = 7;
	const RESET_PASSWORD = 8;

	protected $table = 'action_types';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'label'
	];

	public function customer_log_activities()
	{
		return $this->hasMany(CustomerLogActivity::class, 'action_types_id');
	}
}
