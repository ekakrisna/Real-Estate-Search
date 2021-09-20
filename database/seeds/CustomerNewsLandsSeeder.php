<?php

use App\Models\CustomerNewsLands;
use App\Models\CustomerNewsProperty;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CustomerNewsLandsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        // CustomerNewsLands::truncate();
        Schema::disableForeignKeyConstraints();
        CustomerNewsProperty::truncate();
        Schema::enableForeignKeyConstraints();
        factory(CustomerNewsProperty::class, 50)->create();
    }
}
