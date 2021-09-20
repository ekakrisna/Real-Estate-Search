<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\Company::class, function(Faker $faker){
    return [
        'company_name'      => $faker->company,
        'post_code'         => rand(1000000, 9999999),
        'address'           => $faker->address,
        'phone'             => $faker->e164PhoneNumber,
        'status'            => 'active',
        'created_at'        => Carbon::now(),
        'updated_at'        => Carbon::now()
    ];
});
