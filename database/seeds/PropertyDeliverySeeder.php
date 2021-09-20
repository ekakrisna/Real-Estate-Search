<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\PropertyDelivery;
use Illuminate\Support\Facades\Schema;

class PropertyDeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        // PropertyDelivery::truncate();
        Schema::disableForeignKeyConstraints();
        PropertyDelivery::truncate();
        Schema::enableForeignKeyConstraints();
        $PropertyDelivery = new PropertyDelivery();
        $PropertyDelivery->insert([
            [
                'id'                                                    => 1,
                'properties_id'                                         => 1,
                'subject'                                               => 'property 1 subject for sseder purpose',
                'text'                                                  => 'property 1 terxt for sseder purpose',
                'favorite_registered_area'                              => '1',
                'exclude_received_over_three'                           => '1',
                'exclude_customers_outside_the_budget'                  => '1',
                'exclude_customers_outside_the_desired_land_area'       => '1',
                'created_at'                                            => Carbon::now(),
                'updated_at'                                            => Carbon::now(),
            ],
            [
                'id'                                                    => 2,
                'properties_id'                                         => 2,
                'subject'                                               => 'property 2 subject for sseder purpose',
                'text'                                                  => 'property 2 terxt for sseder purpose',
                'favorite_registered_area'                              => '1',
                'exclude_received_over_three'                           => '1',
                'exclude_customers_outside_the_budget'                  => '1',
                'exclude_customers_outside_the_desired_land_area'       => '1',
                'created_at'                                            => Carbon::now(),
                'updated_at'                                            => Carbon::now(),
            ],
        ]);
    }
}
