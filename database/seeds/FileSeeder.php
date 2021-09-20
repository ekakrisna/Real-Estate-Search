<?php

use Illuminate\Database\Seeder;

use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Support\Facades\Schema;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // File::query()->delete();
        Schema::disableForeignKeyConstraints();
        File::truncate();
        Schema::enableForeignKeyConstraints();
        $arr = array("property_flyer_1", "property_photo_2", "property_photo_1");
        $faker = Factory::create();
        $File = new File();        
        for ($i = 0; $i < 20; $i++) {
            $File->insert([
                [
                    'id'        =>  $i++,
                    'name'      =>  $arr[array_rand($arr)],
                    'extension' =>  'png',
                    'original_name' => $arr[array_rand($arr)],
                    'size_byte' => $faker->randomFloat(6, 5, 10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ]);
        }
        Storage::disk('public')->copy('seeder_image/property_flyer_1.png', 'uploads/properties/property_flyer_1.png');
        Storage::disk('public')->copy('seeder_image/property_photo_1.png', 'uploads/properties/property_photo_1.png');
        Storage::disk('public')->copy('seeder_image/property_photo_2.png', 'uploads/properties/property_photo_2.png');
    }
}
