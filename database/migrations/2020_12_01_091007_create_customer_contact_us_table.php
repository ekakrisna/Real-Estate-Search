<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerContactUsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customer_contact_us', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('customers_id')->nullable()->index('fk_client_contact_us_client_users1_idx');
			$table->unsignedBigInteger('properties_id')->nullable()->index('fk_customer_contact_us_properties1_idx');
			$table->integer('contact_us_types_id')->nullable()->index('fk_customer_contact_us_contact_us_types1_idx');
			$table->string('subject', 100)->nullable();
			$table->text('text')->nullable();
			$table->boolean('flag')->nullable();
			$table->boolean('is_finish')->nullable();
			$table->string('person_in_charge', 45)->nullable();
			$table->text('note')->nullable();
			$table->string('name', 45)->nullable();
			$table->string('email', 191)->nullable();
			$table->string('company_name', 50)->nullable();
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
		Schema::drop('customer_contact_us');
	}

}
