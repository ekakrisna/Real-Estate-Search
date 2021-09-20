<?php

use App\Models\GroupLine;
use Illuminate\Database\Seeder;

class GroupLinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = file_get_contents('database/seeds/files/group_line/group_line.csv');
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
            
            $line_character = $line[1];
            
            // if no similar record, insert record to cities_areas table
            $group_line = GroupLine::updateOrCreate([
                'group_character' => $line_character,
            ]);
        }
    }
}
