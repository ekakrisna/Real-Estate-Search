<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Prefecture
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $name_kana
 * @property string|null $prefecturescol
 * 
 * @property Collection|City[] $cities
 *
 * @package App\Models
 */
class Prefecture extends Model
{
	protected $table = 'prefectures';
	public $timestamps = false;

	const Miyagi_id = 4;

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'name',
	];

	public function cities()
	{
		return $this->hasMany(City::class, 'prefectures_id');
	}

	public function prefecture_areas()
	{
		return $this->hasMany(PrefectureArea::class, 'prefectures_id');
	}
	
}
