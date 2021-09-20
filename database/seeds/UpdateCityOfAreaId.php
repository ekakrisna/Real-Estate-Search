<?php

use Illuminate\Database\Seeder;
use App\Models\City;

class UpdateCityOfAreaId extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $updateDataArray= [
             ["location"=>"仙台市青葉区","area_id"=>44000]
            ,["location"=>"仙台市宮城野区","area_id"=>44000]
            ,["location"=>"仙台市若林区","area_id"=>44000]
            ,["location"=>"仙台市太白区","area_id"=>44000]
            ,["location"=>"仙台市泉区","area_id"=>44000]
            ,["location"=>"石巻市","area_id"=>44000]
            ,["location"=>"塩竈市","area_id"=>44000]
            ,["location"=>"気仙沼市","area_id"=>45000]
            ,["location"=>"白石市","area_id"=>43000]
            ,["location"=>"名取市","area_id"=>43000]
            ,["location"=>"角田市","area_id"=>43000]
            ,["location"=>"多賀城市","area_id"=>44000]
            ,["location"=>"岩沼市","area_id"=>43000]
            ,["location"=>"登米市","area_id"=>42000]
            ,["location"=>"栗原市","area_id"=>42000]
            ,["location"=>"東松島市","area_id"=>44000]
            ,["location"=>"大崎市","area_id"=>42000]
            ,["location"=>"富谷市","area_id"=>42000]
            ,["location"=>"刈田郡蔵王町","area_id"=>43000]
            ,["location"=>"刈田郡七ヶ宿町","area_id"=>43000]
            ,["location"=>"柴田郡大河原町","area_id"=>43000]
            ,["location"=>"柴田郡村田町","area_id"=>43000]
            ,["location"=>"柴田郡柴田町","area_id"=>43000]
            ,["location"=>"柴田郡川崎町","area_id"=>43000]
            ,["location"=>"伊具郡丸森町","area_id"=>43000]
            ,["location"=>"亘理郡亘理町","area_id"=>43000]
            ,["location"=>"亘理郡山元町","area_id"=>43000]
            ,["location"=>"宮城郡松島町","area_id"=>44000]
            ,["location"=>"宮城郡七ヶ浜町","area_id"=>44000]
            ,["location"=>"宮城郡利府町","area_id"=>44000]
            ,["location"=>"黒川郡大和町","area_id"=>42000]
            ,["location"=>"黒川郡大郷町","area_id"=>42000]
            ,["location"=>"黒川郡大衡村","area_id"=>42000]
            ,["location"=>"加美郡色麻町","area_id"=>42000]
            ,["location"=>"加美郡加美町","area_id"=>42000]
            ,["location"=>"遠田郡涌谷町","area_id"=>42000]
            ,["location"=>"遠田郡美里町","area_id"=>42000]
            ,["location"=>"牡鹿郡女川町","area_id"=>45000]
            ,["location"=>"本吉郡南三陸町","area_id"=>45000]
        ];

        foreach($updateDataArray as $updateData){
            City::updateOrCreate(['prefectures_id' => 4,'name' => $updateData['location']],
            ['prefecture_area_id' =>$updateData['area_id']]);
        }
        
    }
}
