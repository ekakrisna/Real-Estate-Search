<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BeforeLoginCustomerLoginLogActivities extends Model
{
    protected $table = 'before_login_customer_log_activities';
    public $timestamps = false;

    protected $fillable = [
        'properties_id',
        'uuid',
        'access_time',
        'before_login_customers_id',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'properties_id');
    }

    public function before_login_customer()
    {
        return $this->belongsTo(Customer::class, 'before_login_customers_id');
    }
}
