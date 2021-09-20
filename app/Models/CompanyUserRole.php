<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CompanyUserRole
 * 
 * @property int $id
 * @property string $name
 * @property string $label
 * 
 * @property Collection|CompanyUser[] $company_users
 *
 * @package App\Models
 */
class CompanyUserRole extends Model
{
    const CORPORATE_MANAGER = 1;
	const TEAM_MANAGER = 2;
	const SALES_STAFF = 3;

	protected $table = 'company_user_roles';
	public $timestamps = false;

	protected $fillable = [
		'name',
		'label'
	];

	public function company_users()
	{
		return $this->hasMany(CompanyUser::class, 'company_user_roles_id');
	}
}
