<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTownsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('towns', function(Blueprint $table)
		{
			$table->foreign('cities_id', 'fk_towns_cities1')->references('id')->on('cities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('towns', function(Blueprint $table)
		{
			$table->dropForeign('fk_towns_cities1');
		});
	}

}
