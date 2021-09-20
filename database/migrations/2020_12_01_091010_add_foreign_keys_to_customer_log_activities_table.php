<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerLogActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_log_activities', function(Blueprint $table)
		{
			$table->foreign('action_types_id', 'fk_customer_log_activities_action_types1')->references('id')->on('action_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('properties_id', 'fk_customer_log_activities_properties1')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('customers_id', 'fk_user_log_activities_copy1_customers1')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_log_activities', function(Blueprint $table)
		{
			$table->dropForeign('fk_customer_log_activities_action_types1');
			$table->dropForeign('fk_customer_log_activities_properties1');
			$table->dropForeign('fk_user_log_activities_copy1_customers1');
		});
	}

}
