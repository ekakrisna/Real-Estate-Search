<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerFavoritePropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_favorite_properties', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('customers_id')->nullable()->index('fk_customer_favorite_properties_customers1_idx');
			$table->unsignedBigInteger('properties_id')->nullable()->index('fk_customer_favorite_properties_properties1_idx');
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
		Schema::drop('customer_favorite_properties');
	}

}
