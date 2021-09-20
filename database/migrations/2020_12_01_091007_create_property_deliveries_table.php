<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyDeliveriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('property_deliveries', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->unsignedBigInteger('properties_id')->nullable()->index('fk_property_deliveries_properties1_idx');
			$table->string('subject', 45)->nullable();
			$table->text('text')->nullable();
			$table->boolean('favorite_registered_area')->nullable();
			$table->boolean('exclude_received_over_three')->nullable();
			$table->boolean('exclude_customers_outside_the_budget')->nullable();
			$table->boolean('exclude_customers_outside_the_desired_land_area')->nullable();
			$table->dateTime('created_at')->nullable();
			$table->dateTime('updated_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('property_deliveries');
	}

}
