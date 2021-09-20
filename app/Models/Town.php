<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Town
 * 
 * @property int $id
 * @property int $cities_id
 * @property string|null $name
 * @property string|null $name_kana
 * 
 * @property City $city
 * @property Collection|CustomerDesiredArea[] $customer_desired_areas
 *
 * @package App\Models
 */
class Town extends Model
{
	protected $table = 'towns';
	public $timestamps = false;

	protected $casts = [
		'id' => 'int',
		'cities_id' => 'int',
		'cities_areas_id' => 'int'
	];

	protected $fillable = [
		'cities_id',
		'name',
		'lat',
		'lng',
		'cities_areas_id'
	];

	public function city()
	{
		return $this->belongsTo(City::class, 'cities_id');
	}

	public function city_areas()
	{
		return $this->belongsTo(CitiesAreas::class, 'cities_area_id');
	}

	public function customer_desired_areas()
	{
		return $this->hasMany(CustomerDesiredArea::class, 'towns_id');
	}

	public function getFullAddressAttribute(){
		$prefectureName = $this->city()->first()->prefecture()->first()['name'];
		$cityName = $this->city()->first()['name'];
		$addressFullName = $prefectureName.$cityName.$this->name;
		return $addressFullName;
	}
}
