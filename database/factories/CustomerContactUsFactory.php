<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use Carbon\Carbon;
// --------------------------------------------------------------------------
use App\Models\CustomerContactUs;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// CustomerContactUs factory
// --------------------------------------------------------------------------
$factory->define( CustomerContactUs::class, function( Faker $faker ){
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
    $data->customers_id = 1;
    $data->properties_id = 1;
    $data->contact_us_types_id = 1;
    // ----------------------------------------------------------------------
    $data->subject = $faker->realText;
    $data->text = $faker->realText;
    // ----------------------------------------------------------------------
    $data->flag = $faker->boolean;
    $data->is_finish = $faker->boolean;
    // ----------------------------------------------------------------------
    $data->person_in_charge = 1;
    // ----------------------------------------------------------------------
    $data->note = $faker->realText;
    $data->name = $faker->name;
    $data->email = $faker->email;
    $data->company_name = $faker->name;
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
// CustomerContactUs initial state
// --------------------------------------------------------------------------
$factory->state( CustomerContactUs::class, 'init', function(){
    $data = new \stdClass;
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $data->id = null;
    $data->customers_id = null;
    $data->properties_id = null;
    $data->contact_us_types_id = null;
    // ----------------------------------------------------------------------
    $data->subject = null;
    $data->text = null;
    // ----------------------------------------------------------------------
    $data->flag = null;
    $data->is_finish = null;
    // ----------------------------------------------------------------------
    $data->person_in_charge = null;
    // ----------------------------------------------------------------------
    $data->note = null;
    $data->name = null;
    $data->email = null;
    $data->company_name = null;
    // ----------------------------------------------------------------------
    $data->created_at = null;
    $data->updated_at = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------