<?php

use App\Models\City;
use App\Models\PrefectureArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = file_get_contents('database/seeds/files/city/city_kana.csv');
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
            if ($count < $header_position) {
                $count++;
                continue;
            }

            $prefecture_id = $line[0];
            $city_name     = $line[1];
            $name_kana     = $line[2];
            $group_line_id = $line[3];

            // if no similar record, insert record to cities_areas table
            $city = City::updateOrCreate(
                ['prefectures_id' => $prefecture_id, 'name'       => $city_name],
                ['name_kana'      => $name_kana, 'group_line_id'  => $group_line_id]
            );
        }
    }
}
