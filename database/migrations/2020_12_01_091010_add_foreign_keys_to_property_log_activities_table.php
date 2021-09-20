<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPropertyLogActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('property_log_activities', function(Blueprint $table)
		{
			$table->foreign('properties_id', 'fk_property_log_activities_properties1')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('property_log_activities', function(Blueprint $table)
		{
			$table->dropForeign('fk_property_log_activities_properties1');
		});
	}

}
