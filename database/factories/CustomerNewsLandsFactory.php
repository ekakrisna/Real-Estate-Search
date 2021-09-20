<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\City;
use App\Models\CompanyUser;
use App\Models\CustomerNew;
use App\Models\CustomerNewsProperty;
use App\Models\Prefecture;
use App\Models\Property;
use App\Models\Town;
use Carbon\Carbon;
use Faker\Generator as Faker;

// --------------------------------------------------------------------------
// Customer factory
// --------------------------------------------------------------------------
$factory->define( CustomerNewsProperty::class, function( Faker $faker ){
    $data = new \stdClass;
    // ----------------------------------------------------------------------    
    // $data->prefectures_id = Prefecture::inRandomOrder()->first();
    // $data->cities_id = City::inRandomOrder()->first();
    // $data->towns_id = Town::inRandomOrder()->first();
    $data->customer_news_id = CustomerNew::inRandomOrder()->first();
    $data->property_id = Property::inRandomOrder()->first();
    // $data->scraping_histories_id = rand(0,1);
    // $data->is_created_news = rand(0,1);
    $data->created_at = Carbon::now()->subMonths(rand(1, 3))->subDays(rand(1, 30));

    // ----------------------------------------------------------------------
    return (array) $data;
    // ----------------------------------------------------------------------
});
// --------------------------------------------------------------------------