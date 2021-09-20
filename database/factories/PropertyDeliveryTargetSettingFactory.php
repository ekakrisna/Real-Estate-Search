<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\PropertyDeliveryTargetSetting;
use Faker\Generator as Faker;

$factory->define( PropertyDeliveryTargetSetting::class, function( Faker $faker ){
    $data = new \stdClass;
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $data->property_deliveries_id = 1;
    $data->companies_id = 1;
    $data->customers_id = 1;
    $data->company_users_id = 1;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});

// --------------------------------------------------------------------------
// Publishing initial state
// --------------------------------------------------------------------------
$factory->state( PropertyDeliveryTargetSetting::class, 'init', function(){
    $data = new \stdClass;
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $data->property_deliveries_id = null;
    $data->companies_id = null;
    $data->customers_id = null;
    $data->company_users_id = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
