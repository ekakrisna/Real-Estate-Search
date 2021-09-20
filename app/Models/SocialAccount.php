<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
	protected $table = 'social_accounts';
	public $timestamps = true;

   	protected $fillable = [
   		'customers_id',
   		'provider_id',
   		'provider_name'
   	];

   	public function socialAccounts(){
    	return $this->belongsTo(Customer::class, 'customers_id');
    }
}
