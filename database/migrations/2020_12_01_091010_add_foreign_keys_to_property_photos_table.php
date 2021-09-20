<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPropertyPhotosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('property_photos', function(Blueprint $table)
		{
			$table->foreign('properties_id', 'fk_properties_file_properties10')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('file_id', 'fk_property_photo_file1')->references('id')->on('files')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('property_photos', function(Blueprint $table)
		{
			$table->dropForeign('fk_properties_file_properties10');
			$table->dropForeign('fk_property_photo_file1');
		});
	}

}
