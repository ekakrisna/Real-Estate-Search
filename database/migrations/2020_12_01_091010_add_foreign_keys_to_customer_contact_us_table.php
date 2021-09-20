<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerContactUsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_contact_us', function(Blueprint $table)
		{
			$table->foreign('customers_id', 'fk_client_contact_us_client_users1')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('contact_us_types_id', 'fk_customer_contact_us_contact_us_types1')->references('id')->on('contact_us_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('properties_id', 'fk_customer_contact_us_properties1')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_contact_us', function(Blueprint $table)
		{
			$table->dropForeign('fk_client_contact_us_client_users1');
			$table->dropForeign('fk_customer_contact_us_contact_us_types1');
			$table->dropForeign('fk_customer_contact_us_properties1');
		});
	}

}
