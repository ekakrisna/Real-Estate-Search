<?php

use App\Models\CompanyUserRole;
use Illuminate\Database\Seeder;

class CompanyUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       	// All delete.
        CompanyUserRole::truncate();
        $CompanyUserRole = new CompanyUserRole();
        $CompanyUserRole->insert([
            // seeder based master data, temporary commented because current login function still
            // depends on the old seeder records
            [
                'id'       			=> 1,
                'name'  			=> 'CORPORATE_MANAGER',
                'Label'      		=> '法人責任者'
            ],
            [
                'id'       			=> 2,
                'name'  			=> 'TEAM_MANAGER',
                'Label'      		=> 'チーム責任者'
            ],
            [
                'id'       			=> 3,
                'name'  			=> 'SALES_STAFF',
                'Label'      		=> '営業担当'
            ]
        ]);
    }
}
