<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerLogActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_log_activities', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('customers_id')->nullable()->index('fk_user_log_activities_copy1_customers1_idx');
			$table->unsignedBigInteger('properties_id')->nullable()->index('fk_customer_log_activities_properties1_idx');
			$table->integer('action_types_id')->index('fk_customer_log_activities_action_types1_idx');
			$table->string('ip', 45);
			$table->dateTime('access_time');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customer_log_activities');
	}

}
