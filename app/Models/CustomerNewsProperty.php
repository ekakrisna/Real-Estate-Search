<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerNewsProperty extends Model
{
    protected $table = 'customer_news_property';
	public $timestamps = false;

    protected $fillable = [
		'customer_news_id',
		'property_id',
		'created_at'
    ];

    public function property()
	{
		return $this->belongsTo(Property::class, 'property_id');
	}

    public function CustomerNew()
	{
		return $this->belongsTo(CustomerNew::class, 'CustomerNew_id');
	}
}
