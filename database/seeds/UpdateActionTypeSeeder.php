<?php

use Carbon\Carbon;
use App\Models\ActionType;
use Illuminate\Database\Seeder;

class UpdateActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ActionType = new ActionType();
        $ActionType->insert([
            [
                'id'    => 9,
                'label' => 'ユーザー情報変更' //CHANGE_USER_INFO
            ],
            [
                'id'    => 10,
                'label' => '新規顧客登録' //CREATE_NEW_CUSTOMER
            ]
        ]);
    }
}
