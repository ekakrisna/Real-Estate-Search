<?php

use Carbon\Carbon;
use App\Models\Scraping;
use Illuminate\Database\Seeder;

class ScrapingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        Scraping::truncate();
        $Scraping = new Scraping();
        $Scraping->insert([
            [
                // 'price'                     => '1000',
                // 'land_status'                  => 'dummy land_status',
                'location'                  => 'dummy location',
                'traffic'                   => 'dummy static',
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
               
            ],
            [
                // 'price'                     => '2000',
                // 'land_status'                  => '2 dummy land_status',
                'location'                  => '2 dummy location',
                'traffic'                   => '2 dummy static',
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
           
            ],
            [
                // 'price'                     => '3000',
                // 'land_status'                  => '3 dummy land_status',
                'location'                  => '3 dummy location',
                'traffic'                   => '3 dummy static',
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            
            ],
        ]);
    }
}
