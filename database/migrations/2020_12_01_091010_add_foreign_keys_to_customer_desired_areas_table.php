<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerDesiredAreasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_desired_areas', function(Blueprint $table)
		{
			$table->foreign('customers_id', 'fk_customer_desired_area_customers1')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('towns_id', 'fk_customer_desired_area_towns1')->references('id')->on('towns')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('cities_id', 'fk_customer_desired_areas_cities1')->references('id')->on('cities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_desired_areas', function(Blueprint $table)
		{
			$table->dropForeign('fk_customer_desired_area_customers1');
			$table->dropForeign('fk_customer_desired_area_towns1');
			$table->dropForeign('fk_customer_desired_areas_cities1');
		});
	}

}
