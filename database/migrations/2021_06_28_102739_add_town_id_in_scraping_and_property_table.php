<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTownIdInScrapingAndPropertyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->bigInteger('town_id')->unsigned()->nullable();
            $table->foreign('town_id')->references('id')->on('towns')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('scrapings', function (Blueprint $table) {
            $table->bigInteger('town_id')->unsigned()->nullable();
            $table->foreign('town_id')->references('id')->on('towns')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
  
    }
}
