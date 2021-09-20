<?php

use Illuminate\Database\Seeder;

use App\Models\PropertyPhoto;
use Illuminate\Support\Facades\Schema;

class PropertyPhotoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PropertyPhoto::query()->delete();
        Schema::disableForeignKeyConstraints();
        PropertyPhoto::truncate();
        Schema::enableForeignKeyConstraints();
        $PropertyPhoto = new PropertyPhoto();
        $PropertyPhoto->insert([
            [
                'id'                =>  1,
                'properties_id'     =>  1,
                'file_id'           =>  2,
                'sort_number'       =>  1,
            ],
            [
                'id'                =>  2,
                'properties_id'     =>  1,
                'file_id'           =>  4,
                'sort_number'       =>  2,
            ],
        ]);
    }
}
