<?php
/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Models\User::class, function(Faker $faker){
    return [
        'belong_company_id'        => rand(1, 10),
        'user_role_id'      => rand(1, 2), // UserRole::ROLE_SUPERVISOR or UserRole::ROLE_OPERATOR
        'display_name'      => $faker->name,
        'email'             => $faker->unique()->safeEmail,
        'password'          => bcrypt('12345678'),
        'created_at'        => Carbon::now(),
        'updated_at'        => Carbon::now(),
    ];
});
