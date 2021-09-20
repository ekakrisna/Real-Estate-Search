<?php

use App\Models\LpScraping;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;


class LpScrapingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        $faker = Faker::create();
        LpScraping::truncate();
        $LpScraping = new LpScraping();
        $LpScraping->insert([
            [                                
                'location'                  => 'dummy location',            
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
                'minimum_price'             => mt_rand(500, 5000),
                'maximum_price'             => mt_rand(1000, 10000),
                'minimum_land_area'         => mt_rand(50, 100),
                'maximum_land_area'         => mt_rand(100, 200),
                'building_area'             => $faker->randomFloat(6, 0, 5),
                'building_age'              => $faker->numberBetween(1,100),
                'house_layout'              => $faker->address,
                'connecting_road'           => $faker->name,
                // 'property_no'               => 3,
                'contracted_years'          => Carbon::now(),

            ],
            [                                
                'location'                  => '2 dummy location',            
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
                'minimum_price'             => mt_rand(500, 5000),
                'maximum_price'             => mt_rand(1000, 10000),
                'minimum_land_area'         => mt_rand(50, 100),
                'maximum_land_area'         => mt_rand(100, 200),
                'building_area'             => $faker->randomFloat(6, 0, 5),
                'building_age'              => $faker->numberBetween(1,100),
                'house_layout'              => $faker->address,
                'connecting_road'           => $faker->name,
                // 'property_no'               => 2,
                'contracted_years'          => Carbon::now(),

            ],
            [                                
                'location'                  => '3 dummy location',            
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
                'minimum_price'             => mt_rand(500, 5000),
                'maximum_price'             => mt_rand(1000, 10000),
                'minimum_land_area'         => mt_rand(50, 100),
                'maximum_land_area'         => mt_rand(100, 200),
                'building_area'             => $faker->randomFloat(6, 0, 5),
                'building_age'              => $faker->numberBetween(1,100),
                'house_layout'              => $faker->address,
                'connecting_road'           => $faker->name,
                // 'property_no'               => 1,
                'contracted_years'          => Carbon::now(),
            ],
        ]);
    }
}
