<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrapingTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('scrapings', function(Blueprint $table)
		{
			$table->bigIncrements('id');
			$table->string('price', 100)->nullable();
			$table->string('landarea', 100)->nullable();
			$table->string('location', 200)->nullable();
			$table->string('land_status', 45)->nullable();
			$table->string('traffic', 100)->nullable();
			$table->string('url', 200)->nullable();
			$table->string('publication_destination', 45)->nullable();
			$table->boolean('is_finish')->nullable();
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
		Schema::drop('scrapings');
	}

}
