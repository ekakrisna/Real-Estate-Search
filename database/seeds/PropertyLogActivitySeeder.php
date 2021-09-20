<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use App\Models\PropertyLogActivity;
use App\Models\PropertyScrapingType;


class PropertyLogActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PropertyLogActivity::query()->delete();

        $PropertyLogActivity = new PropertyLogActivity();
        $PropertyLogActivity->insert([
            [
                'id'                    =>  1,
                'properties_id'         =>  1,
                'before_update_text'    =>  'first property before change 1',
                'after_update_text'     =>  'first property after change 1',
                'created_at'            => Carbon::now()->subDays(3),
                'property_scraping_type_id' =>  PropertyScrapingType::CREATE,
            ],
            [
                'id'                    =>  2,
                'properties_id'         =>  1,
                'before_update_text'    =>  'first property before change 2',
                'after_update_text'     =>  'first property after change 2',
                'created_at'            => Carbon::now()->subDays(2),
                'property_scraping_type_id' =>  PropertyScrapingType::CREATE,
            ],
            [
                'id'                    =>  3,
                'properties_id'         =>  1,
                'before_update_text'    =>  'first property before change 3',
                'after_update_text'     =>  'first property after change 3',
                'created_at'            => Carbon::now()->subDays(1),
                'property_scraping_type_id' =>  PropertyScrapingType::CREATE,
            ],
            [
                'id'                    =>  4,
                'properties_id'         =>  1,
                'before_update_text'    =>  'first property before change 4',
                'after_update_text'     =>  'first property after change 4',
                'created_at'            => Carbon::now(),
                'property_scraping_type_id' =>  PropertyScrapingType::CREATE,
            ],
            [
                'id'                    =>  5,
                'properties_id'         =>  2,
                'before_update_text'    =>  'second property before change 1',
                'after_update_text'     =>  'second property after change 1',
                'created_at'            => Carbon::now()->subDays(3),
                'property_scraping_type_id' =>  PropertyScrapingType::CREATE,
            ],
            [
                'id'                    =>  6,
                'properties_id'         =>  3,
                'before_update_text'    =>  'third property before change 1',
                'after_update_text'     =>  'third property after change 1',
                'created_at'            => Carbon::now()->subDays(3),
                'property_scraping_type_id' =>  PropertyScrapingType::CREATE,
            ],
        ]);
    }
}
