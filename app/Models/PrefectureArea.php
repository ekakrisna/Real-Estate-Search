<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrefectureArea extends Model
{
    protected $table = 'prefecture_areas';
	public $timestamps = false;	

	protected $casts = [
		'id' => 'int'
	];

	protected $fillable = [
		'is_all_show',
        'display_name',
        'prefecture_id'
	];

	public function prefecture()
	{
		return $this->belongsTo(Prefecture::class, 'prefectures_id');
	}

	public function cities()
	{
		return $this->hasMany(City::class, 'prefecture_area_id');
	}
}
