<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertyNumbersEachTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lp_property_publish', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lp_properties_id');
            $table->foreign('lp_properties_id')->references('id')->on('lp_properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->string('publication_destination', 45)->nullable();
            $table->string('property_number', 200)->nullable();
            $table->string('url', 200)->nullable();
        });

        Schema::create('lp_scraping_publish', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('lp_scraping_id');
            $table->foreign('lp_scraping_id')->references('id')->on('lp_scrapings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->string('day_of_week',50)->nullable();;
            $table->string('publication_destination', 45)->nullable();
            $table->string('property_number', 200)->nullable();
            $table->string('url', 200)->nullable();
        });

        Schema::table('property_publish', function (Blueprint $table) {
            $table->string('property_number', 200)->nullable();
        });

        Schema::table('scraping_publish', function (Blueprint $table) {
            $table->string('property_number', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
