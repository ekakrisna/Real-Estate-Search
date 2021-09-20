<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerDesiredAreasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_desired_areas', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('customers_id')->index('fk_customer_desired_area_customers1_idx');
			$table->unsignedBigInteger('cities_id')->index('fk_customer_desired_areas_cities1_idx');
			$table->unsignedBigInteger('towns_id')->nullable()->index('fk_customer_desired_area_towns1_idx');
			$table->boolean('default')->nullable();
			$table->dateTime('created_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customer_desired_areas');
	}

}
