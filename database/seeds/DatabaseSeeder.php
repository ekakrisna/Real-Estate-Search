<?php

use App\Models\LpProperty;
use App\Models\LpPropertyScrapingType;
use App\Models\LpScraping;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * @return void
     */
    public function run()
    {
        /** Clear Uploads Folder **/
        $path = public_path('uploads');
        $file = new Filesystem;
        if (!$file->exists($path)) {
            $file->makeDirectory($path);
        }
        $file->cleanDirectory(public_path('uploads'));

        /** Clear Storage Files, works on s3 driver **/
        $storageFiles =   Storage::allFiles();
        Storage::delete($storageFiles);

        DB::statement("SET foreign_key_checks=0");
        // master
        $this->call(ListConsiderAmountSeeder::class);
        $this->call(ListLandAreaSeeder::class);
        $this->call(PropertyScrapingTypeSeeder::class);
        $this->call(LpPropertyScrapingTypeSeeder::class);
        $this->call(PropertyStatusSeeder::class);
        $this->call(LpPropertyStatusSeeder::class);
        $this->call(ActionTypeSeeder::class);
        $this->call(UpdateActionTypeSeeder::class);
        $this->call(GeneralMaster::class);
        $this->call(PropertyConvertStatus::class);
        $this->call(LpPropertyConvertStatusSeeder::class);

        $this->call(PrefectureAreaSeeder::class);
        $this->call(GroupLinesSeeder::class);

        $this->call(CityAreaSeeder::class);

        // company
        $this->call(CompanyRolesSeeder::class);
        $this->call(CompanyUserRolesSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(CompanyUserSeeder::class);

        if (app()->isLocal()) {
            $this->call(CompanyUserLogActivitySeeder::class);
            $this->call(CustomerSeeder::class);
            $this->call(ScrapingSeeder::class); //check dummy sql
            $this->call(LpScrapingSeeder::class); //check dummy sql
            //$this->call(FullPrefectureSeeder::class);
            $this->call(CitySeeder::class);
            $this->call(Prefecutures_Towns_CitesTableSeeder::class); //check dummy sql

            // property
            $this->call(PropertySeeder::class); //check dummy sql
            $this->call(LpPropertySeeder::class); //check dummy sql
            $this->call(PropertyPublishingSettingSeeder::class);
            $this->call(FileSeeder::class);
            $this->call(PropertyPhotoSeeder::class);
            $this->call(PropertyFlyerSeeder::class);
            $this->call(PropertyLogActivitySeeder::class);
            $this->call(PropertyDeliverySeeder::class);

            // customer
            $this->call(CustomerFavoritePropertySeeder::class);
            $this->call(CustomerLogActivitySeeder::class);
            $this->call(CustomerSearchHistorySeeder::class);
            $this->call(ContactUsTypeSeeder::class);
            $this->call(CustomerContactUsSeeder::class);
            $this->call(CompanyUserTeamSeeder::class);
            $this->call(CustomerNewsSeeder::class);
            $this->call(CustomerNewsLandsSeeder::class);
            $this->call(CustomerDesiredAreaSeeder::class);
        } elseif (app()->runningUnitTests()) {
            $this->call(CustomerSeeder::class);
            $this->call(TestPrefectureSeeder::class);
        } elseif (!app()->runningUnitTests()) {
            $this->call(CitySeeder::class);
            $this->call(Prefecutures_Towns_CitesTableSeeder::class); //check dummy sql
        }
        DB::statement("SET foreign_key_checks=1");
    }
}
