<?php

use App\Models\CitiesAreas;
use App\Models\City;
use App\Models\Customer;
use Carbon\Carbon;
use App\Models\CustomerDesiredArea;
use App\Models\Property;
use App\Models\Town;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CustomerDesiredAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // CustomerDesiredArea::truncate();
        // Schema::disableForeignKeyConstraints();
        // CustomerDesiredArea::truncate();
        // Schema::enableForeignKeyConstraints();
        $CustomerDesiredArea = new CustomerDesiredArea();        
        $property = Property::all()->random(20);
        $i = 1;
        foreach ($property as $key => $value) {
            $data = parseLocation($value->location);
            $townName = $data['town'];
            $town = Town::where('name',$townName)->first();
            $CustomerDesiredArea->insert([
                [
                    'id'            => $i++,
                    'customers_id'  => rand(1, 3),
                    'cities_id'     => 262,
                    'cities_area_id'=> CitiesAreas::inRandomOrder()->first()->id,
                    'default'       => rand(0, 1),
                    'created_at'    => Carbon::now()->subMonths(rand(1, 6))->subDays(rand(1, 30)),
                ],
            ]);
        }
    }
}
