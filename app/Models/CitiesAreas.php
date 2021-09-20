<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CitiesAreas extends Model
{
    protected $table = 'cities_areas';
    public $timestamps = false;    

    protected $casts = [
        'id' => 'int',
        'cities_id' => 'int',
    ];

    protected $fillable = [
        'id',
        'display_name',
        'cities_id',
        'display_name_kana',
        'group_line_id',
    ];

    public function customer_desired_areas()
	{
		return $this->hasMany(CustomerDesiredArea::class, 'cities_area_id');
	}

    public function towns()
	{
		return $this->hasMany(Town::class, 'cities_area_id');
	}

    public function group_line()
	{
		return $this->belongsTo(GroupLine::class, 'group_line_id');
	}
    
}
