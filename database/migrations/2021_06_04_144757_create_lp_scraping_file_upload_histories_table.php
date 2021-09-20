<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLpScrapingFileUploadHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lp_scraping_file_upload_histories', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('file_name');
            $table->dateTime('created_at');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lp_scraping_file_upload_histories');
    }
}
