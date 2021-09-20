<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ListLandArea
 * 
 * @property int $id
 * @property int|null $value
 *
 * @package App\Models
 */
class ListLandArea extends Model
{
	protected $table = 'list_land_areas';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'value' => 'float'
	];

	protected $fillable = [
		'value'
	];
}
