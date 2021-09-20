<?php

use Carbon\Carbon;
use App\Models\CompanyUser;
use App\Models\CompanyUserRole;
use Illuminate\Database\Seeder;

class CompanyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // All delete.
        CompanyUser::truncate();
        $CompanyUser = new CompanyUser();
        if ( app()->isLocal() || app()->runningUnitTests() ) {
            $CompanyUser->insert([
                [
                    'id'                        => 1,
                    'companies_id'              => 1,
                    'company_user_roles_id'     => CompanyUserRole::CORPORATE_MANAGER,
                    'is_active'                 => Null,
                    'name'                      => 'admin',
                    'email'                     => 'admin@admin.com',
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt('12345678'),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ],
                [
                    'id'                        => 2,
                    'companies_id'              => 2,
                    'company_user_roles_id'     => CompanyUserRole::SALES_STAFF,
                    'is_active'                 => Null,
                    'name'                      => 'real estate',
                    'email'                     => 'realestate@admin.com',
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt('12345678'),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ],
                [
                    'id'                        => 3,
                    'companies_id'              => 3,
                    'company_user_roles_id'     => CompanyUserRole::CORPORATE_MANAGER,
                    'is_active'                 => Null,
                    'name'                      => 'house maker',
                    'email'                     => 'housemaker@admin.com',
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt('12345678'),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ],
                [
                    'id'                        => 4,
                    'companies_id'              => 3,
                    'company_user_roles_id'     => CompanyUserRole::CORPORATE_MANAGER,
                    'is_active'                 => Null,
                    'name'                      => 'customer',
                    'email'                     => 'customer@grune.com',
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt('12345678'),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ],
                [
                    'id'                        => 5,
                    'companies_id'              => 3,
                    'company_user_roles_id'     => CompanyUserRole::SALES_STAFF,
                    'is_active'                 => Null,
                    'name'                      => 'second house maker',
                    'email'                     => 'secondhousemaker@admin.com',
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt('12345678'),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ],
                [
                    'id'                        => 6,
                    'companies_id'              => 3,
                    'company_user_roles_id'     => CompanyUserRole::SALES_STAFF,
                    'is_active'                 => Null,
                    'name'                      => 'thrid house maker',
                    'email'                     => 'thridhousemaker@admin.com',
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt('12345678'),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ],
                [
                    'id'                        => 7,
                    'companies_id'              => 3,
                    'company_user_roles_id'     => CompanyUserRole::CORPORATE_MANAGER,
                    'is_active'                 => Null,
                    'name'                      => 'fourth house maker',
                    'email'                     => 'fourthhousemaker@admin.com',
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt('12345678'),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ],
                [
                    'id'                        => 8,
                    'companies_id'              => 3,
                    'company_user_roles_id'     => CompanyUserRole::TEAM_MANAGER,
                    'is_active'                 => Null,
                    'name'                      => 'fifth house maker',
                    'email'                     => 'fifththhousemaker@admin.com',
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt('12345678'),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ],
                [
                    'id'                        => 9,
                    'companies_id'              => 1,
                    'company_user_roles_id'     => CompanyUserRole::TEAM_MANAGER,
                    'is_active'                 => Null,
                    'name'                      => 'admin 2',
                    'email'                     => 'admin2@admin.com',
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt('12345678'),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ],
            ]);
        }else{
            $CompanyUser->insert([
                [
                    'id'                        => 1,
                    'companies_id'              => 1,
                    'company_user_roles_id'     => CompanyUserRole::CORPORATE_MANAGER,
                    'is_active'                 => Null,
                    'name'                      => "管理者",
                    'email'                     => config('accounts.admin_email'),
                    'email_verified_at'         => Carbon::now(),
                    'password'                  => bcrypt(config('accounts.admin_password')),
                    'remember_token'            => '',
                    'created_at'                => Carbon::now(),
                    'updated_at'                => Carbon::now(),
                    'deleted_at'                => Null,
                ]
            ]);
        }
    }
}