<?php

use Illuminate\Database\Seeder;

use App\Models\PropertyFlyer;
use Illuminate\Support\Facades\Schema;

class PropertyFlyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PropertyFlyer::query()->delete();
        Schema::disableForeignKeyConstraints();
        PropertyFlyer::truncate();
        Schema::enableForeignKeyConstraints();

        $PropertyFlyer = new PropertyFlyer();
        $PropertyFlyer->insert([
            [
                'id'                =>  1,
                'properties_id'     =>  1,
                'file_id'           =>  6,
                'sort_number'       =>  1,
            ],
            [
                'id'                =>  2,
                'properties_id'     =>  1,
                'file_id'           =>  8,
                'sort_number'       =>  2,
            ],
        ]);
    }
}
