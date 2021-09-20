<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\PropertyPublish;
use Faker\Generator as Faker;

// --------------------------------------------------------------------------
// PropertyPublish factory
// --------------------------------------------------------------------------
$factory->define( PropertyPublish::class, function( Faker $faker ){
    $data = new \stdClass;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Add Japanese faker
    // ----------------------------------------------------------------------
    $faker->addProvider( new \Faker\Provider\Internet( $faker ));
    $faker->addProvider( new \Faker\Provider\Japanese( $faker ));
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $data->properties_id = 1;
    $data->publication_destination = 1;
    // ----------------------------------------------------------------------
    $data->url = $faker->realText;
    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
// --------------------------------------------------------------------------
// PropertyPublish initial state
// --------------------------------------------------------------------------
$factory->state( PropertyPublish::class, 'init', function(){
    $data = new \stdClass;
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $data->properties_id = null;
    // ----------------------------------------------------------------------
    $data->publication_destination = null;
    $data->url = null;    

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------