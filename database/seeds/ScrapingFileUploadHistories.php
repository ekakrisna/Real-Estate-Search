<?php

use App\Models\ScrapingFileUploadHistories as ModelsScrapingFileUploadHistories;
use Illuminate\Database\Seeder;

class ScrapingFileUploadHistories extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(ModelsScrapingFileUploadHistories::class, 50)->create();
    }
}
