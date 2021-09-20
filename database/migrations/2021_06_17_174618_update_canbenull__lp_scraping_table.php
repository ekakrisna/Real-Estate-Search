<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCanbenullLpScrapingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lp_scrapings', function (Blueprint $table) {
            $table->bigInteger('property_no')->nullable()->change(); // "property_no" is abolished!! please refer "*_publish.property_number"
            $table->integer('minimum_price')->nullable()->change();
            $table->integer('maximum_price')->nullable()->change();
            $table->decimal('minimum_land_area', 12, 6)->nullable()->change();
            $table->decimal('maximum_land_area', 12, 6)->nullable()->change();
            $table->decimal('building_area', 12, 6)->nullable()->change();
            $table->integer('building_age')->nullable()->change();
            $table->string('house_layout', 100)->nullable()->nullable()->change();
            $table->string('connecting_road', 200)->nullable()->change();
            $table->dateTime('contracted_years')->nullable()->change();
            $table->dropColumn('property_no');
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
