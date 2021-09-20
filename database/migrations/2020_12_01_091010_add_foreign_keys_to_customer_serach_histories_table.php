<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerSerachHistoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customer_search_histories', function(Blueprint $table)
		{
			$table->foreign('customers_id', 'fk_customer_search_histories_customers1')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customer_search_histories', function(Blueprint $table)
		{
			$table->dropForeign('fk_customer_search_histories_customers1');
		});
	}

}
