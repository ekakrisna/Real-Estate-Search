<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Company
 * 
 * @property int $id
 * @property int|null $company_roles_id
 * @property string $company_name
 * @property string $post_code
 * @property string $address
 * @property string $phone
 * @property bool|null $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * 
 * @property Collection|CompanyUser[] $company_users
 *
 * @package App\Models
 */
class Company extends Model
{
	use SoftDeletes;

	protected $table = 'companies';
	public $timestamps = true;
	protected $appends = [ 'url', 'ja' ];

	protected $casts = [
		'company_roles_id' => 'int',
		'is_active' => 'bool'
	];

	protected $fillable = [
		'company_roles_id',
		'company_name',
		'post_code',
		'address',
		'phone',
		'is_active'
	];

	public function company_users()
	{
		return $this->hasMany(CompanyUser::class, 'companies_id');
    }

    public function users(){
		return $this->hasMany( CompanyUser::class, 'companies_id' );
    }
    
    public function role(){
		return $this->belongsTo( CompanyRole::class, 'company_roles_id' );
	}

	public function company_roles()
	{
		return $this->belongsTo(CompanyRole::class, 'company_roles_id');
	}

	// ----------------------------------------------------------------------
    // Get Japanese formatted timestamps
    // ----------------------------------------------------------------------
    public function getJaAttribute(){
        $result = new \stdClass; $format = "Y年m月d日 H:i";
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
        $url->edit = route('admin.company.edit', $this->id);
		$url->view = '#';
		$url->user = route('admin.company.user.list', $this->id);

        $permission = new \stdClass;
        $url->permission = $permission;

        $permission->index = route( 'admin.company.user.permission', $this->id );
        $permission->upload = route( 'admin.company.user.permission.upload', $this->id );
        $permission->addTeam = route( 'admin.company.user.permission.addTeam', $this->id );

        return $url;
    }
    // ----------------------------------------------------------------------
}
