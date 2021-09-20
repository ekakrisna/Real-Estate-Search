<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class City
 * 
 * @property int $id
 * @property int $prefectures_id
 * @property string|null $name
 * @property string|null $name_kana
 * @property string|null $citiescol
 * 
 * @property Prefecture $prefecture
 * @property Collection|CustomerDesiredArea[] $customer_desired_areas
 * @property Collection|Town[] $towns
 *
 * @package App\Models
 */
class City extends Model
{
	protected $table = 'cities';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'prefectures_id' => 'int',
		'prefecture_area_id' => 'int',
		'group_line_id' => 'int',
	];

	protected $fillable = [
		'prefectures_id',
		'name',
		'lat',
		'lng',
		'prefecture_area_id',
		'name_kana',
        'group_line_id',
	];

	public function prefecture()
	{
		return $this->belongsTo(Prefecture::class, 'prefectures_id');
	}

	public function customer_desired_areas()
	{
		return $this->hasMany(CustomerDesiredArea::class, 'cities_id');
	}

	public function towns()
	{
		return $this->hasMany(Town::class, 'cities_id');
	}

	public function cities_areas()
	{
		return $this->hasMany(CitiesAreas::class, 'cities_id');
	}

	public function group_line()
	{
		return $this->belongsTo(GroupLine::class, 'group_line_id');
	}
}
