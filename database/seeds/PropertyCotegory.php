<?php

use Illuminate\Database\Seeder;
use App\Models\LpPropertyCotegory;

class PropertyCotegory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $LpPropertyCotegory = new LpPropertyCotegory();
        $LpPropertyCotegory->insert([
            [
                'id'    => 1,
                'display_name' => '新築戸建',                
            ],
            [
                'id'    => 2,
                'display_name' => '中古戸建',                
            ]
        ]);        
    }
}
