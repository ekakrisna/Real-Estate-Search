<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('properties', function(Blueprint $table)
		{
			$table->foreign('companies_id', 'fk_properties_companies1')->references('id')->on('companies')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('property_publishing_settings_id', 'fk_properties_property_publishing_settings1')->references('id')->on('property_publishing_settings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('property_statuses_id', 'fk_properties_property_statuses1')->references('id')->on('property_statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('scraping_id', 'fk_properties_scraping_suumo1')->references('id')->on('scrapings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('properties', function(Blueprint $table)
		{
			$table->dropForeign('fk_properties_companies1');
			$table->dropForeign('fk_properties_property_publishing_settings1');
			$table->dropForeign('fk_properties_property_statuses1');
			$table->dropForeign('fk_properties_scraping_suumo1');
		});
	}

}
