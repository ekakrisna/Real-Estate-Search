<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerFavoritePropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_favorite_properties', function(Blueprint $table)
		{
			$table->foreign('customers_id', 'fk_ customer_favorite_properties_customers1')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('properties_id', 'fk_ customer_favorite_properties_properties1')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_favorite_properties', function(Blueprint $table)
		{
			$table->dropForeign('fk_ customer_favorite_properties_customers1');
			$table->dropForeign('fk_ customer_favorite_properties_properties1');
		});
	}

}
