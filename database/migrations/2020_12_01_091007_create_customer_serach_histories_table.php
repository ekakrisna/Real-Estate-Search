<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerSerachHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_search_histories', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('customers_id')->nullable()->index('fk_customer_search_histories_customers1_idx');
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
		Schema::drop('customer_search_histories');
	}

}
