<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLpScrapingLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lp_scraping_logs', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->integer('status');            
            $table->boolean('is_adapt')->default(0);
            $table->dateTime('created_at');
            $table->bigInteger('lp_scrapings_id')->unsigned()->nullable();
            $table->foreign('lp_scrapings_id')->references('id')->on('lp_scrapings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lp_scraping_logs');
    }
}
