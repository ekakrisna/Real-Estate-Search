<?php

use Illuminate\Database\Seeder;
use App\Models\Prefecture;
use App\Models\City;
use App\Models\Town;

class Prefecutures_Towns_CitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $header_position = 1;

        /**
         * get file list
         */
        $dir = 'database/seeds/files/';
        $filelist = glob($dir . '*');

        foreach ($filelist as $file) {
            
            //read file
            $data = file_get_contents($file);
            $data = mb_convert_encoding($data, 'UTF-8', 'SJIS-win');
            $temp = tmpfile();
            $meta = stream_get_meta_data($temp);
            fwrite($temp, $data);
            rewind($temp);
    
            $file = new SplFileObject($meta['uri'], 'rb');
    
            $file->setFlags(
                SplFileObject::READ_CSV |
                SplFileObject::READ_AHEAD |
                SplFileObject::SKIP_EMPTY |
                SplFileObject::DROP_NEW_LINE
            );
    
            $count = 0;
            foreach ($file as $line) {
                if($count < $header_position){
                    $count++;
                    continue;
                }

                $prefecture_name = $line[1];    
                $city_name =  $line[3];
                $town_name = $line[5];
                $common_name = "";
                $town_lat = $line[6];
                $town_lng = $line[7];
    
                // prefecture create or update
                $prefectureModel = Prefecture::updateOrCreate(
                    ['name' =>  $prefecture_name]
                );
    
                // city create or update
                $cityModel = City::updateOrCreate(
                    ['prefectures_id' => $prefectureModel->id,'name' => $city_name]
                );
    
                // town create or update
                $townModel = Town::updateOrCreate(
                    ['cities_id' => $cityModel->id,'name' => $town_name.$common_name],
                    ['lat'=>$town_lat,'lng'=>$town_lng]
                );
            }
        }
        

        $dir = 'database/seeds/files/city_lat_lng/';
        $filelist = glob($dir . '*');
        foreach ($filelist as $file) {
            
            //read file
            $data = file_get_contents($file);
        
            $temp = tmpfile();
            $meta = stream_get_meta_data($temp);
            fwrite($temp, $data);
            rewind($temp);
    
            $file = new SplFileObject($meta['uri'], 'rb');
    
            $file->setFlags(
                SplFileObject::READ_CSV |
                SplFileObject::READ_AHEAD |
                SplFileObject::SKIP_EMPTY |
                SplFileObject::DROP_NEW_LINE
            );
            $file->setCsvControl("\t");

            foreach ($file as $line) {

                $prefecture_name = $line[1];  
                $city_name =  $line[2].$line[3];
                $town_lat = $line[4];
                $town_lng = $line[5];

                // prefecture create or update
                $prefectureModel = Prefecture::updateOrCreate(
                    ['name' =>  $prefecture_name]
                );        
    
                // city create or update
                $cityModel = City::updateOrCreate(
                    ['prefectures_id' => $prefectureModel->id,'name' => $city_name],
                    ['lat'=>$town_lat,'lng'=>$town_lng]
                );
            }
        }
    }
}
