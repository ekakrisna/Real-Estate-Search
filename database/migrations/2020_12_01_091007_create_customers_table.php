<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('company_users_id')->unsigned()->nullable()->index('fk_client_users_company_users1_idx');
			$table->string('name', 45);
			$table->string('email', 191)->nullable();
			$table->string('password', 191)->nullable();
			$table->string('phone', 20)->nullable();
			$table->boolean('flag')->nullable();
			$table->boolean('is_cancellation')->nullable();
			$table->boolean('not_leave_record')->nullable();
			$table->integer('minimum_price_id')->nullable()->index('fk_customers_list_consider_amount1_idx');
			$table->integer('maximum_price_id')->nullable()->index('fk_customers_list_consider_amount2_idx');
			$table->integer('minimum_price_land_area_id')->nullable()->index('fk_customers_list_consider_amount3_idx');
			$table->integer('maximum_price_land_area_id')->nullable()->index('fk_customers_list_consider_amount4_idx');
			$table->integer('minimum_land_area_id')->nullable()->index('fk_customers_list_land_area1_idx');
			$table->integer('maximum_land_area_id')->nullable()->index('fk_customers_list_land_area2_idx');
			$table->boolean('license')->nullable();
			$table->string('remember_token', 191)->nullable();
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
		Schema::drop('customers');
	}

}
