<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrapingLandStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scraping_land_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('scraping_id')->unsigned();
            $table->foreign('scraping_id')->references('id')->on('scrapings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->string('text',45);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scraping_land_statuses');
    }
}
