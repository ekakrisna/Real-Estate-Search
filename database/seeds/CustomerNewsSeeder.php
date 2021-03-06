<?php

use App\Models\CustomerNew;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CustomerNewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        CustomerNew::truncate();
        Schema::enableForeignKeyConstraints();
        $CustomerNew = new CustomerNew();
        $CustomerNew->insert([
            [
                'customers_id'          => 1,
                'type'                  => 1,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 2,
                'is_show'               => 0,                
                'location'              => "宮城県仙台市青葉区大町一丁目",
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 3,
                'is_show'               => 1,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 3,
                'is_show'               => 0,                
                'location'              => '大通西十三丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 3,
                'is_show'               => 0,                
                'location'              => '台場一条三丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 4,
                'is_show'               => 0,                
                'location'              => '台場一条三丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 1,
                'is_show'               => 0,                
                'location'              => '江向',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 2,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 3,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 4,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ], 
            [
                'customers_id'          => 1,
                'type'                  => 1,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 2,
                'is_show'               => 0,                
                'location'              => "宮城県仙台市青葉区大町一丁目",
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 3,
                'is_show'               => 1,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 3,
                'is_show'               => 0,                
                'location'              => '大通西十三丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 3,
                'is_show'               => 0,                
                'location'              => '台場一条三丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 4,
                'is_show'               => 0,                
                'location'              => '台場一条三丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 1,
                'is_show'               => 0,                
                'location'              => '江向',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 2,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 3,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 4,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],         
            [
                'customers_id'          => 1,
                'type'                  => 2,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 2,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 2,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],                   
            [
                'customers_id'          => 1,
                'type'                  => 2,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],
            [
                'customers_id'          => 1,
                'type'                  => 2,
                'is_show'               => 0,                
                'location'              => '宮城県仙台市青葉区大町一丁目',
                'property_deliveries_id'=> 1,
                // 'customer_news_lands_id'=> 1,
                'is_send_email'        => 0,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ],       
        ]);
    }
}
