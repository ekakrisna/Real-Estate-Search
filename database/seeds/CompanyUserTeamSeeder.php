<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\CompanyUserTeam;
use Illuminate\Support\Facades\Schema;

class CompanyUserTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        // CompanyUserTeam::truncate();
        Schema::disableForeignKeyConstraints();
        CompanyUserTeam::truncate();
        Schema::enableForeignKeyConstraints();
        $CompanyUserTeam = new CompanyUserTeam();
        $CompanyUserTeam->insert([
            [
                'id'                        => 1,
                'leader_id'                 => 1,
                'member_id'                 => 3,
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ],
            [
                'id'                        => 2,
                'leader_id'                 => 1,
                'member_id'                 => 6,
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ],
            [
                'id'                        => 3,
                'leader_id'                 => 1,
                'member_id'                 => 7,
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ],
            [
                'id'                        => 4,
                'leader_id'                 => 2,
                'member_id'                 => 3,
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ],
            [
                'id'                        => 5,
                'leader_id'                 => 2,
                'member_id'                 => 4,
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ],
            [
                'id'                        => 6,
                'leader_id'                 => 2,
                'member_id'                 => 4,
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ],
            [
                'id'                        => 7,
                'leader_id'                 => 2,
                'member_id'                 => 3,
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ],
            [
                'id'                        => 8,
                'leader_id'                 => 1,
                'member_id'                 => 9,
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ],
            [
                'id'                        => 9,
                'leader_id'                 => 2,
                'member_id'                 => 7,
                'created_at'                => Carbon::now(),
                'updated_at'                => Carbon::now(),
            ],
        ]);
    }
}