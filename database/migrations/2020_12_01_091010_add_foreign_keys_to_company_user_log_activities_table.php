<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCompanyUserLogActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('company_user_log_activities', function(Blueprint $table)
		{
			$table->foreign('company_users_id', 'fk_user_log_activities_users1')->references('id')->on('company_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('company_user_log_activities', function(Blueprint $table)
		{
			$table->dropForeign('fk_user_log_activities_users1');
		});
	}

}
