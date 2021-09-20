<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('properties', function(Blueprint $table)
		{
			$table->bigIncrements('id', true);
			$table->bigInteger('property_statuses_id')->index('fk_properties_property_statuses1_idx');
			$table->bigInteger('property_publishing_settings_id')->nullable()->index('fk_properties_property_publishing_settings1_idx');
			$table->bigInteger('scraping_id')->unsigned()->nullable()->index('fk_properties_scraping_suumo1_idx');
			$table->bigInteger('companies_id')->unsigned()->nullable()->index('fk_properties_companies1_idx');
			$table->text('location')->nullable();
			$table->decimal('minimum_land_area',8,2)->nullable();
			$table->decimal('maximum_land_area',8,2)->nullable();
			$table->decimal('minimum_land_area_tsubo',8,2)->nullable();
			$table->decimal('maximum_land_area_tsubo',8,2)->nullable();
			$table->integer('minimum_price')->nullable();
			$table->integer('maximum_price')->nullable();
			$table->string('contact_us', 100)->nullable();
			$table->string('publication_destination', 45)->nullable();
			$table->text('publication_destination_link')->nullable();
			$table->dateTime('publish_date')->nullable();
			$table->boolean('building_conditions')->nullable();
			$table->text('building_conditions_desc')->nullable();
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
		Schema::drop('properties');
	}

}
