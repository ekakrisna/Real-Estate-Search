<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ListConsiderAmount
 * 
 * @property int $id
 * @property int|null $value
 *
 * @package App\Models
 */
class ListConsiderAmount extends Model
{
	protected $table = 'list_consider_amounts';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'value' => 'int'
	];

	protected $fillable = [
		'value'
	];

}
