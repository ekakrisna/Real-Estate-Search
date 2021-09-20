<?php

use App\Models\PrefectureArea;
use Illuminate\Database\Seeder;

class PrefectureAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PrefectureArea::truncate();
        $PrefectureArea = new PrefectureArea();
        $PrefectureArea->insert([
                [
                    'id'       =>  41000,
                    'display_name'    =>  '全域',
                    'is_all_show'   => '1',
                    'prefecture_id'   => '4',
                 ],
                 [
                  'id'       =>  41500,
                  'display_name'    =>  '仙台市(青葉区、宮城野区、太白区、その他)',
                  'is_all_show'   => '0',
                  'prefecture_id'   => '4',
               ],
                 [
                    'id'       =>  42000,
                    'display_name'    =>  '県北エリア(大崎、富谷、黒川郡、その他)',
                    'is_all_show'  => '0',
                    'prefecture_id'  => '4',
                 ],
                 [
                    'id'       =>  43000,
                    'display_name'    =>  '県南エリア(名取、岩沼、白石、その他)',
                    'is_all_show'   => '0',
                    'prefecture_id'   => '4',
                 ],
                 [
                    'id'       =>  44000,
                    'display_name'    =>  '県東エリア(多賀城、利府、塩釜、その他)',
                    'is_all_show'   => '0',
                    'prefecture_id'   => '4',
                 ],
                 [
                    'id'       =>  45000,
                    'display_name'    =>  '太平洋沿岸エリア(気仙沼、女川、南三陸町)',
                    'is_all_show'   => '0',
                    'prefecture_id'   => '4',
                 ],                 
        ]);    
    }
}
