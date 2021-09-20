<?php

use Illuminate\Database\Seeder;
use App\Models\Prefecture;
use App\Models\City;
use App\Models\Town;

class TestPrefectureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prefecture_name = "宮城県";
        $prefecture_name_kana = "ミヤギケン";
        $cityArray = ["仙台市青葉区","仙台市宮城野区"];
        $townArray = [1 => ["荒巻本沢三丁目","上杉六丁目","角五郎二丁目","角五郎四丁目","角五郎一丁目","角五郎二丁目"],2 => ["松岡町","西宮城野","鶴巻二丁目"]];

        // prefecture create or update
        $prefectureModel = Prefecture::updateOrCreate(
            ['name' =>  $prefecture_name]
        );

        // city create or update
        foreach($cityArray as $city){
            $cityModel = City::updateOrCreate(
                ['prefectures_id' => $prefectureModel->id,'name' => $city]
            );
        }
   
        for($i = 1 ; $i < count($townArray) + 1; $i++) {
            for($j = 0; $j < count($townArray[$i]); $j++){
                $townModel = Town::updateOrCreate(
                    ['cities_id' => $i,'name' => $townArray[$i][$j]],
                    ['lat'=>000,'lng'=>000,'name_kana'=>""]
                );
            }
        }
    }
}
