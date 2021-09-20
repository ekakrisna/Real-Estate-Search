<?php


use Carbon\Carbon;
use App\Models\PropertyPublishingSetting;
use Illuminate\Database\Seeder;

class PropertyPublishingSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PropertyPublishingSetting::truncate();
        $PropertyPublishingSetting = new PropertyPublishingSetting();
        $PropertyPublishingSetting->insert([
            [
                'id'                => 1,
                'companies_id'      => 3,
                'company_users_id'  => 8,
                'customers_id'      => 19,
                'properties_id'     => 2
            ],
            [
                'id'                => 2,
                'companies_id'      => 2,
                'company_users_id'  => 2,
                'customers_id'      => 18,
                'properties_id'     => 2
            ],
            [
                'id'                => 3,
                'companies_id'      => 3,
                'company_users_id'  => 5,
                'customers_id'      => 2,
                'properties_id'     => 2
            ],
            [
                'id'                => 4,
                'companies_id'      => 3,
                'company_users_id'  => 6,
                'customers_id'      => 3,
                'properties_id'     => 2
            ]
        ]);
    }
}
