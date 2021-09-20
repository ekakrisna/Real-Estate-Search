<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrapingPublishCopy1Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::create('scraping_publish_copy1', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('publication_destination', 45);
            $table->string('url');
            $table->bigInteger('lp_scrapings_id')->unsigned()->nullable();
            $table->foreign('lp_scrapings_id')->references('id')->on('lp_scrapings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scraping_publish_copy1');
    }
}
