<?php

use App\Models\LpPropertyScrapingType;
use Illuminate\Database\Seeder;

class LpPropertyScrapingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LpPropertyScrapingType::query()->delete();

        $LpPropertyScrapingType = new LpPropertyScrapingType();
        $LpPropertyScrapingType->insert([
            [
                'id'                =>  LpPropertyScrapingType::CREATE,
                'label'     =>  LpPropertyScrapingType::JPN_TEXT[LpPropertyScrapingType::CREATE],
            ],
            [
                'id'                =>  LpPropertyScrapingType::UPDATE,
                'label'     =>  LpPropertyScrapingType::JPN_TEXT[LpPropertyScrapingType::UPDATE],
            ],
            [
                'id'                =>  LpPropertyScrapingType::DELETE,
                'label'     =>  LpPropertyScrapingType::JPN_TEXT[LpPropertyScrapingType::DELETE],
            ],
        ]);
    }
}
