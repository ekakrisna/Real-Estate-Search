<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScrapingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scraping_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('scraping_id')->unsigned();
            $table->foreign('scraping_id')->references('id')->on('scrapings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->Integer('status');
            $table->boolean('is_adapt');
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
        Schema::dropIfExists('scraping_logs');
    }
}
