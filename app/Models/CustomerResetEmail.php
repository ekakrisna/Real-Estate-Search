<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerResetEmail extends Model
{
    protected $table = 'customers_reset_emails';
	public $timestamps = true;

	protected $fillable = [
        'customer_id',
		'is_adaptation',
        'new_email',
		'token',        
	];

    public function customer()
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}
}
