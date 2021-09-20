<?php

use Illuminate\Database\Seeder;
use App\Models\CitiesAreas;
use App\Models\Town;

class CityAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = file_get_contents('database/seeds/files/city_area/city_area.csv');
        // given sample is not needed to be converted into UTF-8
        // $data = mb_convert_encoding($data, 'UTF-8', 'SJIS-win');
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

        $header_position = 1;
        $count = 0;
        foreach ($file as $line) {
            if($count < $header_position){
                $count++;
                continue;
            }

            $town_id        = $line[0];
            $city_id        = $line[1];    
            $town_name      = $line[2];
            $city_area_name = $line[3];
            $group_character= $line[4];
            $group_line_id  = $line[5];            

            // if no similar record, insert record to cities_areas table
            $cityArea = CitiesAreas::updateOrCreate([                
                'display_name'      => $city_area_name,
                'cities_id'         => $city_id],[
                    'display_name_kana' => $group_character,
                    'group_line_id'     => $group_line_id
                ]
            );

            $town = Town::find($town_id);
            if($town == null){continue;}
            $town->cities_area_id = $cityArea->id;
            $town->save();
        }
    }
}
