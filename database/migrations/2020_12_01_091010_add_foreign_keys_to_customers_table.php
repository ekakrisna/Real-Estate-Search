<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('customers', function(Blueprint $table)
		{
			$table->foreign('company_users_id', 'fk_client_users_company_users1')->references('id')->on('company_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('minimum_price_id', 'fk_customers_list_consider_amount1')->references('id')->on('list_consider_amounts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('maximum_price_id', 'fk_customers_list_consider_amount2')->references('id')->on('list_consider_amounts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('minimum_price_land_area_id', 'fk_customers_list_consider_amount3')->references('id')->on('list_consider_amounts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('maximum_price_land_area_id', 'fk_customers_list_consider_amount4')->references('id')->on('list_consider_amounts')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('minimum_land_area_id', 'fk_customers_list_land_area1')->references('id')->on('list_land_areas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('maximum_land_area_id', 'fk_customers_list_land_area2')->references('id')->on('list_land_areas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('customers', function(Blueprint $table)
		{
			$table->dropForeign('fk_client_users_company_users1');
			/*
			$table->dropForeign('fk_customers_list_consider_amount1');
			$table->dropForeign('fk_customers_list_consider_amount2');
			$table->dropForeign('fk_customers_list_consider_amount3');
			$table->dropForeign('fk_customers_list_consider_amount4');
			$table->dropForeign('fk_customers_list_land_area1');
			$table->dropForeign('fk_customers_list_land_area2');
			*/
		});
	}

}
