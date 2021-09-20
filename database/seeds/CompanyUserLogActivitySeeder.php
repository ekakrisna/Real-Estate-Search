<?php

use Carbon\Carbon;
use App\Models\CompanyUserLogActivity;
use Illuminate\Database\Seeder;

class CompanyUserLogActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CompanyUserLogActivity::truncate();
        $CompanyUserLogActivity = new CompanyUserLogActivity();
        $CompanyUserLogActivity->insert([
            [
                'id'       			    => 1,
                'company_users_id'      => 1,
                'activity'              => '新規登録',
                'detail'                => 'Dummy Detail 1',
                'ip'                    => '192.168.1.1',
                'access_time'           => Carbon::now(),
            ],
            [
                'id'                    => 2,
                'company_users_id'      => 1,
                'activity'              => 'ログイン',
                'detail'                => 'Dummy Detail 2',
                'ip'                    => '192.168.1.1',
                'access_time'           => Carbon::now(),
            ],
            [
                'id'                    => 3,
                'company_users_id'      => 5,
                'activity'              => 'My設定変更',
                'detail'                => 'Dummy Detail 3',
                'ip'                    => '192.168.1.1',
                'access_time'           => Carbon::now(),
            ],
            [
                'id'                    => 4,
                'company_users_id'      => 6,
                'activity'              => '物件閲覧',
                'detail'                => 'Dummy Detail 4',
                'ip'                    => '192.168.1.1',
                'access_time'           => Carbon::now(),
            ],
            [
                'id'                    => 5,
                'company_users_id'      => 7,
                'activity'              => 'お気に入り登録',
                'detail'                => 'Dummy Detail 5',
                'ip'                    => '192.168.1.1',
                'access_time'           => Carbon::now(),
            ]
        ]);
    }
}
