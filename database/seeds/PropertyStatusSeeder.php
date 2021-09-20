<?php

use Carbon\Carbon;
use App\Models\PropertyStatus;
use Illuminate\Database\Seeder;

class PropertyStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        PropertyStatus::truncate();
        $PropertyStatus = new PropertyStatus();
        $PropertyStatus->insert([
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
                'name'      => 'PULICATION_LIMITED',
                'label'     => '掲載(限定)',
            ],
            [
                'id'        => 4,
                'name'      => 'NOT_POSTED',
                'label'     => '非掲載',
            ],
            [
                'id'        => 5,
                'name'      => 'FINISH_PUBLISH',
                'label'     => '掲載終了',
            ],
        ]);
    }
}
