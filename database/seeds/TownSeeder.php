<?php

use App\Models\Town;
use Illuminate\Database\Seeder;

class TownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        Town::truncate();
        $Town = new Town();
        $Town->insert([
            [
                'cities_id'    		=> 1,
                'name'              => 'Town1',
                'name_kana'      	=> 'Town1',
            ],
            [
                'cities_id'    		=> 1,
                'name'              => 'Town2',
                'name_kana'      	=> 'Town2',
            ],
            [
                'cities_id'    		=> 1,
                'name'              => 'Town3',
                'name_kana'      	=> 'Town3',
            ],
            [
                'cities_id'    		=> 2,
                'name'              => 'Town4',
                'name_kana'      	=> 'Town4',
            ],
            [
                'cities_id'    		=> 2,
                'name'              => 'Town5',
                'name_kana'      	=> 'Town5',
            ],
            [
                'cities_id'    		=> 2,
                'name'              => 'Town6',
                'name_kana'      	=> 'Town6',
            ],
            [
                'cities_id'    		=> 3,
                'name'              => 'Town7',
                'name_kana'      	=> 'Town7',
            ],
            [
                'cities_id'    		=> 3,
                'name'              => 'Town8',
                'name_kana'      	=> 'Town8',
            ],

            [
                'cities_id'    		=> 3,
                'name'              => 'Town9',
                'name_kana'      	=> 'Town9',
            ],
        ]);
    }
}
