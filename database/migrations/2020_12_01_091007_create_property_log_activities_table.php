<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyLogActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('property_log_activities', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->unsignedBigInteger('properties_id')->nullable()->index('fk_property_log_activities_properties1_idx');
			$table->text('before_update_text')->nullable();
			$table->text('after_update_text')->nullable();
			$table->dateTime('created_at')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('property_log_activities');
	}

}
