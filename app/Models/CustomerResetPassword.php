<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerResetPassword extends Model
{
    protected $table = 'customers_reset_password';
	public $timestamps = true;

	protected $fillable = [
        'customer_id',
		'is_adaptation',        
		'token',        
	];

    public function customer()
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}
}
