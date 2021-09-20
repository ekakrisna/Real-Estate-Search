<?php
// --------------------------------------------------------------------------
use Carbon\Carbon;
use Faker\Generator as Faker;
// --------------------------------------------------------------------------
use App\Models\Customer;
// --------------------------------------------------------------------------

// --------------------------------------------------------------------------
// Customer factory
// --------------------------------------------------------------------------
$factory->define( Customer::class, function( Faker $faker ){
    $data = new \stdClass;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    // Add Japanese faker
    // ----------------------------------------------------------------------
    $faker->addProvider( new \Faker\Provider\Internet( $faker ));
    //$faker->addProvider( new \Faker\Provider\Japanese( $faker ));
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $data->company_users_id = 1;
    // ----------------------------------------------------------------------
    $data->name = $faker->lastName;
    $data->email = $faker->email;
    $data->password = $faker->password;
    $data->phone = $faker->phoneNumber;
    // ----------------------------------------------------------------------
    $data->flag = $faker->boolean;
    $data->is_cancellation = $faker->boolean;
    $data->not_leave_record = $faker->boolean;
    // ----------------------------------------------------------------------
    $data->minimum_price = $faker->numberBetween( 100, 500 );
    $data->maximum_price = $faker->numberBetween( 500, 1000 );

    $data->minimum_price_land_area = $faker->numberBetween( 100, 500 );
    $data->maximum_price_land_area = $faker->numberBetween( 500, 1000 );
    
    $data->minimum_land_area = $faker->randomFloat( 6, 0, 5 );
    $data->maximum_land_area = $faker->randomFloat( 6, 5, 10 );
    // ----------------------------------------------------------------------
    $data->license = $faker->boolean;
    $data->remember_token = null;
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
// Customer initial state
// --------------------------------------------------------------------------
$factory->state( Customer::class, 'init', function(){
    $data = new \stdClass;
    // ----------------------------------------------------------------------
    
    // ----------------------------------------------------------------------
    $data->company_users_id = null;
    // ----------------------------------------------------------------------
    $data->name = null;
    $data->email = null;
    $data->password = null;
    $data->phone = null;
    // ----------------------------------------------------------------------
    $data->flag = null;
    $data->is_cancellation = null;
    $data->not_leave_record = null;
    // ----------------------------------------------------------------------
    $data->minimum_price = null;
    $data->maximum_price = null;

    $data->minimum_price_land_area = null;
    $data->maximum_price_land_area = null;
    
    $data->minimum_land_area = null;
    $data->maximum_land_area = null;
    // ----------------------------------------------------------------------
    $data->license = null;
    $data->remember_token = null;
    // ----------------------------------------------------------------------
    $data->created_at = null;
    $data->updated_at = null;
    // ----------------------------------------------------------------------

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------
