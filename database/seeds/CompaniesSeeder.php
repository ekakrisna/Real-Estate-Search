<?php

use App\Models\Company;
use App\Models\CompanyRole;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class CompaniesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * name: use on control identifier.
     * label: display label.
     *
     * @return void
     */
    public function run(){
        // All delete.
        Company::truncate();
        $Company = new Company();
        if ( app()->isLocal() || app()->runningUnitTests() ) {
            $Company->insert([
                [
                    'id'       			=> 1,
                    'company_roles_id'  => CompanyRole::ROLE_ADMIN,
                    'company_name'      => 'Grune',
                    'post_code'      	=> '123',
                    'address'      		=> 'Japan',
                    'phone'      		=> '123',
                    'is_active'      	=> 1,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                    'deleted_at'        => Null,
                ],
                [
                    'id'       			=> 2,
                    'company_roles_id'  => CompanyRole::ROLE_ADMIN,
                    'company_name'      => 'Real Estate',
                    'post_code'      	=> '123',
                    'address'      		=> 'Japan',
                    'phone'      		=> '123',
                    'is_active'      	=> 1,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                    'deleted_at'        => Null,
                ],
                [
                    'id'       			=> 3,
                    'company_roles_id'  => CompanyRole::ROLE_HOME_MAKER,
                    'company_name'      => 'House Maker',
                    'post_code'      	=> '123',
                    'address'      		=> 'Japan',
                    'phone'      		=> '123',
                    'is_active'      	=> 1,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                    'deleted_at'        => Null,
                ]
            ]);
        }
        else { 
            $Company->insert([
                [
                    'id'       			=> 1,
                    'company_roles_id'  => CompanyRole::ROLE_ADMIN,
                    'company_name'      => '開日ホールディングス',
                    'post_code'      	=> '984-0001',
                    'address'      		=> '宮城県仙台市若林区鶴代町３番１５号',
                    'phone'      		=> '12345678',
                    'is_active'      	=> 1,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                    'deleted_at'        => Null,
                ]
            ]);
        }
      
    }
}
