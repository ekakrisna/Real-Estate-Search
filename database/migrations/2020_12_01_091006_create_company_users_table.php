<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_users', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('companies_id')->unsigned()->nullable()->index('users_belong_company_id_foreign');
			$table->bigInteger('company_user_roles_id')->unsigned()->nullable()->index('users_user_role_id_foreign');
			$table->boolean('is_active')->nullable();
			$table->string('name', 191);
			$table->string('email', 191)->unique('users_email_unique');
			$table->dateTime('email_verified_at')->nullable();
			$table->string('password', 191);
			$table->string('remember_token', 100)->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		//DB::statement('ALTER TABLE  `company_users` DROP PRIMARY KEY , ADD PRIMARY KEY (`id` ,  `remember_token` );');
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_users');
	}

}
