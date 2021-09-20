<?php

use Carbon\Carbon;
use App\Models\ActionType;
use Illuminate\Database\Seeder;

class ActionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        ActionType::truncate();
        $ActionType = new ActionType();
        $ActionType->insert([
            [
                'id'    => 1,
                'label' => '新規登録' //SIGN_UP
            ],
            [
                'id'    => 2,
                'label' => 'ログイン' //LOGIN
            ],
            [
                'id'    => 3,
                'label' => 'My設定変更' //CHANGE_MY_SETTING
            ],
            [
                'id'    => 4,
                'label' => '物件閲覧' //PROPERTY_BROWSING
            ],
            [
                'id'    => 5,
                'label' => 'お気に入り登録' //PROPERTY_FAVORITES
            ],
            [
                'id'    => 6,
                'label' => 'お問い合わせ' //CONTACT_US
            ],
            [
                'id'    => 7,
                'label' => '利用停止' //SUSPENSION_OF_USE
            ],
            [
                'id'    => 8,
                'label' => 'パスワード再設定' //RESET_PASSWORD
            ]
        ]);
    }
}
