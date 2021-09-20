<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyPublish extends Model
{
    protected $table = 'property_publish';
    public $timestamps = false;

    protected $fillable = [
        'properties_id',
        'publication_destination',
        'url',
        'property_number'
	];

    public function property()
	{
		return $this->belongsTo(Property::class, 'property_id');
	}

    public function scopePropertyId($query,$id){
        return $query->where('properties_id', $id);
    }

    public function setPropertyIdAttribute($value)
    {
        $this->attributes['properties_id'] = $value;
    }
}
