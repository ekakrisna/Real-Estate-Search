<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CompanyUserTeam
 * 
 * @property int $id
 * @property int $leader
 * @property int $member
 * 
 * @property CompanyUser $company_user
 *
 * @package App\Models
 */
class CompanyUserTeam extends Model
{
	protected $table = 'company_user_teams';
	protected $primaryKey = 'id';
	public $timestamps = true;

	protected $casts = [
		'id' => 'int',
		'leader_id' => 'int',
		'member_id' => 'int'
	];

	protected $fillable = [
		'id',
		'leader_id',
		'member_id'
	];

	public function company_user()
	{
		return $this->belongsTo(CompanyUser::class, 'member_id');
	}
}
