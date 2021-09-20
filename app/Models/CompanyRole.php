<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CompanyRole
 * 
 * @property int $id
 * @property string $name
 * @property string $label
 *
 * @package App\Models
 */
class CompanyRole extends Model
{
	protected $table = 'company_roles';

	// const values : Please use this if it need to refer on source-code(logic).
    const ROLE_ADMIN = 1;
	const ROLE_HOME_MAKER = 2;
	
	// modelname based master data, temporary commented because current login function still
    // depends on the old modelname
	// const ADMIN 			= 1;
    // const HOME_MAKER 	= 2;

	public $timestamps = false;

	protected $fillable = [
		'name',
		'label'
	];

	// relation has many rules for admin
    public function admin(){
        return $this->hasMany('App\Models\CompanyUser');
    }
}
