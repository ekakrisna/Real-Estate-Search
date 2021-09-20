<?php

use App\Models\LpPropertyStatus;
use Illuminate\Database\Seeder;

class LpPropertyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //LpPropertyStatus::truncate();
        $LpPropertyStatus = new LpPropertyStatus();
        $LpPropertyStatus->insert([
            [
                'id'        => 1,
                'name'      => 'APPROVAL_PENDING',
                'label'     => '承認待ち',
            ],
            [
                'id'        => 2,
                'name'      => 'PUBLISHED',
                'label'     => '掲載',
            ],
            [
                'id'        => 3,
                'name'      => 'NOT_POSTED',
                'label'     => '非掲載',
            ],
            [
                'id'        => 4,
                'name'      => 'NOT_PUBLISH',
                'label'     => '掲載終了',
            ],
        ]);
    }
}
