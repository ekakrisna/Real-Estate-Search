<?php

use App\Models\PropertyConvertStatus as ModelsPropertyConvertStatus;
use Illuminate\Database\Seeder;

class PropertyConvertStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ModelsPropertyConvertStatus::truncate();
        $PropertyConvertStatus = new ModelsPropertyConvertStatus();
        $PropertyConvertStatus->insert([
            [
                'id'    => 0,
                'label' => '正常終了',                
            ],
            [
                'id'    => 100,
                'label' => '住所違い',                
            ],
            [
                'id'    => 200,
                'label' => '料金違い',                
            ],
            [
                'id'    => 300,
                'label' => '土地面積違い',                
            ],
            [
                'id'    => 999,
                'label' => '更新済み',                
            ],
            [
                'id'    => 1000,
                'label' => '不整合データバックアップ',                
            ]
        ]);        		
    }
}
