<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyPublishingSettingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('property_publishing_settings', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('companies_id')->unsigned()->nullable()->index('fk_property_publishing_settings_companies1_idx');
			$table->bigInteger('company_users_id')->unsigned()->nullable()->index('fk_property_publishing_settings_company_users1_idx');
            $table->bigInteger('customers_id')->nullable()->index('fk_property_publishing_settings_property_customers1_idx');
            $table->string('type', 64)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('property_publishing_settings');
	}

}
