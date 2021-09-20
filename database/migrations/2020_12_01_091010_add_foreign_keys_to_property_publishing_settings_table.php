<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPropertyPublishingSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('property_publishing_settings', function(Blueprint $table)
		{
			$table->foreign('companies_id', 'fk_property_publishing_settings_companies1')->references('id')->on('companies')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('company_users_id', 'fk_property_publishing_settings_company_users1')->references('id')->on('company_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('customers_id', 'fk_property_publishing_settings_property_customers1')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('property_publishing_settings', function(Blueprint $table)
		{
			$table->dropForeign('fk_property_publishing_settings_companies1');
			$table->dropForeign('fk_property_publishing_settings_company_users1');
			$table->dropForeign('fk_property_publishing_settings_property_customers1');
		});
	}

}
