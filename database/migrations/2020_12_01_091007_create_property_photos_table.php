<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyPhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('property_photos', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->unsignedBigInteger('properties_id')->index('fk_properties_file_properties1_idx');
			$table->bigInteger('file_id')->index('fk_property_photo_file1_idx');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('property_photos');
	}

}
