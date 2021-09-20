<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTownsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('towns', function(Blueprint $table)
		{
			$table->bigInteger('id',true)->unsigned();
			$table->unsignedBigInteger('cities_id')->index('fk_towns_cities1_idx');
			$table->string('name', 200)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('towns');
	}

}
