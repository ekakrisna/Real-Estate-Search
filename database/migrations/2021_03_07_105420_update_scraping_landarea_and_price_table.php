<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateScrapingLandareaAndPriceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scrapings', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('landarea');
            $table->decimal('minimum_land_area',12,6)->nullable();
			$table->decimal('maximum_land_area',12,6)->nullable();
            $table->bigInteger('minimum_price')->nullable();
			$table->bigInteger('maximum_price')->nullable();
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
        //
    }
}
