<?php

use App\Models\Property;
use App\Models\PropertyPublish as ModelsPropertyPublish;
use Illuminate\Database\Seeder;

class PropertyPublish extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModelsPropertyPublish::truncate();
        $counter = 0;
        $Property = Property::count();        
        for ($x = 0; $x <= $Property; $x++) {
            $counter++;            
            $PropertyPublish = new ModelsPropertyPublish();                                                       
            $PropertyPublish->insert([
                'id'                        => $counter,
                'properties_id'             => Property::select('id')->inRandomOrder()->first()->id,                                
                'publication_destination'   => 'Publication Destination Dummy '. $counter,                
                'url'                       => 'https://www.google.com/',
            ]);
        }  
    }
}
