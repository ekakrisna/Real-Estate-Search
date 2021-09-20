<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpPropertyPublish extends Model
{
    protected $table = 'lp_property_publish';
    public $timestamps = false;
    
    protected $fillable = [
        'lp_properties_id',
        'publication_destination',
        'url',
        'property_number'
	];

    public function property()
	{
		return $this->belongsTo(LpProperty::class, 'lp_property_id');
	}

    public function scopePropertyId($query,$id){
        return $query->where('lp_properties_id', $id);
    }

    public function setPropertyIdAttribute($value)
    {
        $this->attributes['lp_properties_id'] = $value;
    }
}
