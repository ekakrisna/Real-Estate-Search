<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupLine extends Model
{
    protected $table = 'group_line';
	public $timestamps = false;

	protected $fillable = [
        'id',
		'group_character',
	];

	public function cities_areas()
	{
		return $this->hasMany(CitiesAreas::class, 'group_line_id');
	}

	public function cities()
	{
		return $this->hasMany(City::class, 'group_line_id');
	}
}
