<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrapingPublish extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scraping_publish', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('scraping_id')->unsigned();
            $table->foreign('scraping_id')->references('id')->on('scrapings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->string('publication_destination', 45)->nullable();
            $table->string('url', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scraping_publish');
    }
}
