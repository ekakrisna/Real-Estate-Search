<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\App;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminResetPassword;

/**
 * Class CompanyUser
 * 
 * @property int $id
 * @property int|null $companies_id
 * @property int|null $company_user_roles_id
 * @property bool|null $is_active
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Company|null $company
 * @property CompanyUserRole|null $company_user_role
 * @property Collection|CompanyUserLogActivity[] $company_user_log_activities
 * @property Collection|CompanyUserSerachHistory[] $company_user_serach_histories
 * @property Collection|CompanyUserTeam[] $company_user_teams
 *
 * @package App\Models
 */
class CompanyUser extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
	
    use Authenticatable, Authorizable, CanResetPassword, Notifiable, SoftDeletes;

    protected $table = 'company_users';
	public $timestamps = true;
    protected $appends = [ 'url', 'ja' ];

	protected $casts = [
		'id' => 'int',
		'companies_id' => 'int',
		'company_user_roles_id' => 'int',
		'is_active' => 'bool'
	];

	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'companies_id',
		'company_user_roles_id',
		'is_active',
		'name',
		'email',
		'email_verified_at',
		'password'
	];

	public function company() {
		return $this->belongsTo( Company::class, 'companies_id' );
    }
    
    public function role() {
		return $this->belongsTo( CompanyUserRole::class, 'company_user_roles_id' );
	}

	public function company_user_role()
	{
		return $this->belongsTo(CompanyUserRole::class, 'company_user_roles_id');
	}

	public function company_user_log_activities()
	{
		return $this->hasMany(CompanyUserLogActivity::class, 'company_users_id');
	}

	public function company_user_serach_histories()
	{
		return $this->hasMany(CompanyUserSerachHistory::class, 'company_users_id');
	}

	public function company_user_teams()
	{
		return $this->hasMany(CompanyUserTeam::class, 'member_id');
    }
    
    public function company_user_teams_leader()
	{
		return $this->hasMany(CompanyUserTeam::class, 'leader_id');
	}

    public function customer()
    {
        return $this->hasMany(Customer::class, 'company_users_id');
    }

    public function customers(){
        return $this->hasMany( Customer::class, 'company_users_id' );
    }

    public function latestLogActivities()
    {
        return $this->hasOne(CompanyUserLogActivity::class, 'company_users_id')->orderBy('id', 'DESC');
    }

	/**
     * Return true if User has permission of edit company.
     *
     * Use like this : if( $user->has_permit_edit_company ) { ... }
     * ( * Laravel will make property automatically)
     */
    public function getHasPermitEditCompanyAttribute()
    {
        return $this->admin_role_id == AdminRole::ROLE_SUPER_ADMIN
            || $this->admin_role_id == AdminRole::ROLE_GENERAL_ADMIN;
    }

    /**
     * Return true if User has permission of edit admin.
     *
     * Use like this : if( $user->has_permit_edit_admin ) { ... }
     */
    public function getHasPermitEditAdminAttribute()
    {
        return $this->admin_role_id == AdminRole::ROLE_SUPER_ADMIN
            || $this->admin_role_id == AdminRole::ROLE_GENERAL_ADMIN;
    }

    // ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute(){
        $result = new \stdClass; $format = "Y年m月d日 h:i";
        if( !empty( $this->created_at )) $result->created_at = Carbon::parse( $this->created_at )->format( $format );
        if( !empty( $this->updated_at )) $result->updated_at = Carbon::parse( $this->updated_at )->format( $format );
        return $result;
    }
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // @TODO: Build URL based on route
    // ----------------------------------------------------------------------
    public function getUrlAttribute(){
        if( empty( $this->id )) return null;
        $url = new \stdClass;
        $url->index = route('admin.company.user.list', [ $this->companies_id ]);
        $url->edit = route('admin.company.user.edit', [ $this->companies_id, $this->id ]);
        $url->view = '#';
        return $url;
    }
    // ----------------------------------------------------------------------
}
// --------------------------------------------------------------------------
