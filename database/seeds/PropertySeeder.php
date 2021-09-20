<?php

use App\Models\City;
use Carbon\Carbon;
use App\Models\Property;
use Illuminate\Database\Seeder;
use App\Models\PropertyScrapingType;
use Illuminate\Support\Facades\Schema;

use App\Models\ListConsiderAmount;
use App\Models\ListLandArea;
use App\Models\Prefecture;
use App\Models\PropertyConvertStatus;
use App\Models\Town;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $min = 0;
    private $max = 0;
    private $min_land = 0;
    private $max_land = 0;

    public function min_price()
    {
        $min_price = ListConsiderAmount::inRandomOrder()->first();
        $this->min = $min_price->value;
        return $this->min;
    }

    public function max_price($min_price)
    {
        $max_price = ListConsiderAmount::where('value', '>=', $min_price)->inRandomOrder()->first();
        $this->max = $max_price->value;
        return $this->max;
    }

    public function min_land()
    {
        $min_land = ListLandArea::inRandomOrder()->first();
        $this->min_land = $min_land->value;
        return $this->min_land;
    }

    public function max_land($min_lands)
    {
        $max_land = ListLandArea::where('value', '>=', $min_lands)->inRandomOrder()->first();
        $this->max_land = $max_land->value;
        return $this->max_land;
    }

    public function rand_scraping($type)
    {
        if ($type == "CREATE") {
            return PropertyScrapingType::CREATE;
        } elseif ($type == "DELETE") {
            return PropertyScrapingType::DELETE;
        } else {
            return PropertyScrapingType::UPDATE;
        }
    }

    public function run()
    {
        // All delete.
        Schema::disableForeignKeyConstraints();
        Property::truncate();
        Schema::enableForeignKeyConstraints();
        $PropertyScrapingType = array(0 => "CREATE", 1 => "UPDATE", 2 => "DELETE");
        $counter = 0;

        // Same Location For C11
        $prefecture = Prefecture::where('id', 10)->first();

        $town = Town::with('city')->where('cities_id', 486)->get();
        
        $namePrefecture = $prefecture->name;
        foreach ($town as $keyCity => $valueCity) {            
            $nameTown = $valueCity->name;
            $nameCity = $valueCity->city->name;            
            foreach ($town as $keyTowns => $valueTown) {
                $nameTown = $valueTown->name;
                $address['fulllocation'][] = $namePrefecture . $nameCity . $nameTown;
            }
        }

        $dataLocation = array_rand($address['fulllocation'], 100);
        foreach ($dataLocation as $key => $value) {
            for ($x = 0; $x <= 15; $x++) {
                $counter++;
                $Property = new Property();
                if (rand(0, 1) == 1) {
                    $Property->insert([
                        'id'                                => $counter,
                        'property_statuses_id'              => rand(1, 3),
                        'scraping_id'                       => rand(1, 3),
                        'companies_id'                      => rand(1, 3),
                        'location'                          => $address['fulllocation'][$value],
                        'property_scraping_type_id'         => $this->rand_scraping($PropertyScrapingType[array_rand($PropertyScrapingType, 1)]),

                        'minimum_price'                      => $this->min_price(),
                        'maximum_price'                      => $this->max_price($this->min),
                        'minimum_land_area'                 => $this->min_land(),
                        'maximum_land_area'                 => $this->max_land($this->min_land),
                        // 'land_status'                       => 'Property Dummy Land Status'. $counter,

                        'contact_us'                        => 'Property Dummy ' . $counter,
                        'publish_date'                      => Carbon::now()->toDateTimeString(),
                        'building_conditions'               => 1,
                        'building_conditions_desc'          => 'Description Property Dummy Conditon ' . $counter,
                        'created_at'                        => Carbon::now()->subDays(rand(1,100))->toDateTimeString(),
                        'updated_at'                        => Carbon::now(),
                        'property_convert_status_id'        => PropertyConvertStatus::select('id')->inRandomOrder()->first()->id,
                        // 'is_conversion'                     => rand(0, 1),
                        'is_reserve'                        => rand(0, 1),
                        'is_bug_report'                     => rand(0, 1)

                    ]);
                } else {
                    $Property->insert([
                        'id'                                => $counter,
                        'property_statuses_id'              => rand(1, 3),
                        'scraping_id'                       => rand(1, 3),
                        'companies_id'                      => rand(1, 3),
                        'location'                          => $address['fulllocation'][$value],
                        'property_scraping_type_id'         => $this->rand_scraping($PropertyScrapingType[array_rand($PropertyScrapingType, 1)]),

                        'minimum_price'                      => $this->min_price(),
                        'maximum_price'                      => $this->max_price($this->min),
                        'minimum_land_area'                 => $this->min_land(),
                        'maximum_land_area'                 => $this->max_land($this->min_land),
                        // 'land_status'                       => 'Property Dummy Land Status'. $counter,

                        'contact_us'                        => 'Property Dummy ' . $counter,
                        'publish_date'                      => Carbon::now()->toDateTimeString(),
                        'building_conditions'               => 0,
                        'created_at'                        => Carbon::now()->subDays(rand(1,100))->toDateTimeString(),
                        'updated_at'                        => Carbon::now(),
                        'property_convert_status_id'        => PropertyConvertStatus::select('id')->inRandomOrder()->first()->id,
                        // 'is_conversion'                     => rand(0, 1),
                        'is_reserve'                        => rand(0, 1),
                        'is_bug_report'                     => rand(0, 1)

                    ]);
                }
            }
        }
    }
}
