<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeforeLoginCustomers extends Model
{
    protected $table = 'before_login_customers';    

    protected $fillable = [
        'uuid',
        'created_at',
        'updated_at',
    ];
}
