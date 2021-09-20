<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerSignUp extends Model
{
    //
    protected $table = 'customer_sign_ups';
    public $timestamps = true;
    protected $fillable = [
        'email',
        'token',
        'is_adapt',
    ];
    protected $casts =[
        'is_adapt' => 'boolean'
    ];
}
