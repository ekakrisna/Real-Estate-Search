<?php

use App\Models\GeneralMaster as ModelsGeneralMaster;
use Illuminate\Database\Seeder;

class GeneralMaster extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModelsGeneralMaster::truncate();
        $ModelsGeneralMaster = new ModelsGeneralMaster();
        $ModelsGeneralMaster->insert([
            [
                'id'    => 1,
                'key_name' => 'unit_scroll_amount',                 
                'value'     => 500,
            ],
            [
                'id'    => 2,
                'key_name' => 'unit_scroll_land',                
                'value'     => 1,
            ],            
        ]);       
    }
}
