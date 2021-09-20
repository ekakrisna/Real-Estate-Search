<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLpScrapingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lp_scrapings', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('location')->nullable();
            $table->bigInteger('property_no'); // "property_no" is abolished!! please refer "*_publish.property_number"
            $table->integer('minimum_price');
            $table->integer('maximum_price');
            $table->decimal('minimum_land_area', 12, 6);
            $table->decimal('maximum_land_area', 12, 6);
            $table->decimal('building_area', 12, 6);
            $table->integer('building_age');
            $table->string('house_layout', 100)->nullable();
            $table->string('connecting_road', 200)->nullable();
            $table->dateTime('contracted_years');
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
        Schema::dropIfExists('lp_scrapings');
    }
}
