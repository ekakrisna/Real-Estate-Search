<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Carbon\Carbon;
// --------------------------------------------------------------------------
use App\Models\PropertyDelivery;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// PropertyDelivery factory
// --------------------------------------------------------------------------
$factory->define( PropertyDelivery::class, function( Faker $faker ){
    $data = new \stdClass;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Add Japanese faker
    // ----------------------------------------------------------------------
    $faker->addProvider( new \Faker\Provider\Internet( $faker ));
    //$faker->addProvider( new \Faker\Provider\Japanese( $faker ));
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $data->id = 1;
    $data->properties_id = 1;
    // ----------------------------------------------------------------------
    $data->subject = $faker->realText;
    $data->text = $faker->realText;
    // ----------------------------------------------------------------------
    $data->favorite_registered_area = $faker->boolean;
    $data->exclude_received_over_three = $faker->boolean;
    $data->exclude_customers_outside_the_budget = $faker->boolean;
    $data->exclude_customers_outside_the_desired_land_area = $faker->boolean;
    // ----------------------------------------------------------------------
    $data->created_at = Carbon::now();
    $data->updated_at = Carbon::now();
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------


// --------------------------------------------------------------------------
// PropertyDelivery initial state
// --------------------------------------------------------------------------
$factory->state( PropertyDelivery::class, 'init', function(){
    $data = new \stdClass;
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $data->id = null;
    $data->properties_id = null;
    // ----------------------------------------------------------------------
    $data->subject = null;
    $data->text = null;
    // ----------------------------------------------------------------------
    $data->favorite_registered_area = null;
    $data->exclude_received_over_three = null;
    $data->exclude_customers_outside_the_budget = null;
    $data->exclude_customers_outside_the_desired_land_area = null;
    // ----------------------------------------------------------------------
    $data->created_at = null;
    $data->updated_at = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
