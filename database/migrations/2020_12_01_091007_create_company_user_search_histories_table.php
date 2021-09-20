<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyUserSearchHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_user_search_histories', function(Blueprint $table)
		{
			$table->bigInteger('id',true)->unsigned();
			$table->bigInteger('company_users_id')->unsigned()->nullable()->index('fk_user_log_activities_users1_idx');
			$table->string('location', 100)->nullable();
			$table->string('minimum_price', 45)->nullable();
			$table->string('maximum_price', 45)->nullable();
			$table->string('minimum_land_area', 45)->nullable();
			$table->string('maximum_land_area', 45)->nullable();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('company_user_search_histories');
	}

}
