<?php

use Illuminate\Database\Seeder;
use App\Models\PropertyScrapingType;

class PropertyScrapingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PropertyScrapingType::query()->delete();

        $PropertyScrapingType = new PropertyScrapingType();
        $PropertyScrapingType->insert([
            [
                'id'                =>  PropertyScrapingType::CREATE,
                'label'     =>  PropertyScrapingType::JPN_TEXT[PropertyScrapingType::CREATE],
            ],
            [
                'id'                =>  PropertyScrapingType::UPDATE,
                'label'     =>  PropertyScrapingType::JPN_TEXT[PropertyScrapingType::UPDATE],
            ],
            [
                'id'                =>  PropertyScrapingType::DELETE,
                'label'     =>  PropertyScrapingType::JPN_TEXT[PropertyScrapingType::DELETE],
            ],
        ]);

    }
}
