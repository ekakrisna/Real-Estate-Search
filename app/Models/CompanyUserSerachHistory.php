<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CompanyUserSerachHistory
 * 
 * @property int $id
 * @property int|null $company_users_id
 * @property string|null $location
 * @property string|null $minimum_price
 * @property string|null $maximum_price
 * @property string|null $minimum_land_area
 * @property string|null $maximum_land_area
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property CompanyUser|null $company_user
 *
 * @package App\Models
 */
class CompanyUserSerachHistory extends Model
{
	protected $table = 'company_user_serach_histories';

	protected $casts = [
		'id' => 'int',
		'company_users_id' => 'int'
	];

	protected $fillable = [
		'company_users_id',
		'location',
		'minimum_price',
		'maximum_price',
		'minimum_land_area',
		'maximum_land_area'
	];

	public function company_user()
	{
		return $this->belongsTo(CompanyUser::class, 'company_users_id');
	}
}
