<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCompanyUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_users', function(Blueprint $table)
		{
			$table->foreign('companies_id', 'users_belong_company_id_foreign')->references('id')->on('companies')->onUpdate('CASCADE')->onDelete('SET NULL');
			$table->foreign('company_user_roles_id', 'users_user_role_id_foreign')->references('id')->on('company_user_roles')->onUpdate('CASCADE')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company_users', function(Blueprint $table)
		{
			$table->dropForeign('users_belong_company_id_foreign');
			$table->dropForeign('users_user_role_id_foreign');
		});
	}

}
