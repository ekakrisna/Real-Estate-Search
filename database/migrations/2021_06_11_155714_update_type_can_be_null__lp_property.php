<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTypeCanBeNullLpProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lp_properties', function (Blueprint $table) {
            $table->dropColumn('scraping_id');
            $table->dropColumn('scraping_type_id');
            $table->bigInteger('property_no')->nullable()->change(); // "property_no" is abolished!! please refer "*_publish.property_number"
            $table->text('location')->nullable()->change();
            $table->integer('minimum_price')->nullable()->change();
            $table->integer('maximum_price')->nullable()->change();
            $table->decimal('minimum_land_area', 12, 6)->nullable()->change();
            $table->decimal('maximum_land_area', 12, 6)->nullable()->change();
            $table->decimal('building_area', 12, 6)->nullable()->change();

            $table->integer('building_age')->nullable()->change();
            $table->string('house_layout')->nullable()->change();
            $table->string('connecting_road')->nullable()->change();
            $table->string('contracted_years')->nullable()->change();
            $table->dateTime('publish_date')->nullable()->change();
            $table->string('traffic')->nullable()->change();
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
