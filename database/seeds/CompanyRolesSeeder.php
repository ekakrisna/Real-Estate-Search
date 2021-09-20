<?php

use App\Models\CompanyRole;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CompanyRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // All delete.
        CompanyRole::truncate();
        $CompanyRole = new CompanyRole();
        $CompanyRole->insert([
            //
            //[
            //    'id'       			=> CompanyRole::ROLE_SUPER_ADMIN,
            //    'name'  			=> 'super_admin',
            //    'Label'      		=> 'Super Admin'
            //],
            //[
            //    'id'       			=> CompanyRole::ROLE_REAL_ESTATE,
            //    'name'  			=> 'real_estate',
            //    'Label'      		=> 'Real Estate Agent'
            //],
            //[
            //   	'id'       			=> CompanyRole::ROLE_HOUSE_MAKER,
            //    'name'  			=> 'house_maker',
            //    'Label'      		=> 'House Maker'
            //]

            // seeder based master data, temporary commented because current login function still
            // depends on the old seeder records
            [
                'id'       			=> CompanyRole::ROLE_ADMIN,
                'name'  			=> 'ADMIN',
                'Label'      		=> 'admin(不動産)'
            ],
            [
                'id'       			=> CompanyRole::ROLE_HOME_MAKER,
                'name'  			=> 'HOME_MAKER',
                'Label'      		=> 'ハウスメーカ'
            ]
        ]);
    }
}
