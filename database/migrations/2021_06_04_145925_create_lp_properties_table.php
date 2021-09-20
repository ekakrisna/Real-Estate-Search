<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLpPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lp_properties', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('lp_scrapings_id')->unsigned();
            $table->foreign('lp_scrapings_id')->references('id')->on('lp_scrapings')->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->integer('lp_property_scraping_type_id')->unsigned()->nullable();
            $table->foreign('lp_property_scraping_type_id')->references('id')->on('lp_property_scraping_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->bigInteger('lp_property_status_id')->unsigned()->nullable();
            $table->foreign('lp_property_status_id')->references('id')->on('lp_property_statuses')->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->integer('lp_property_convert_status_id')->nullable();
            $table->foreign('lp_property_convert_status_id')->references('id')->on('lp_property_convert_status')->onUpdate('NO ACTION')->onDelete('NO ACTION');

            // "property_no" is abolished!! please refer "*_publish.property_number"
            $table->bigInteger('property_no')->unsigned();
            $table->bigInteger('scraping_id')->unsigned();

            $table->integer('scraping_type_id');

            $table->text('location');

            $table->integer('minimum_price');
            $table->integer('maximum_price');
            $table->decimal('minimum_land_area', 12, 6);
            $table->decimal('maximum_land_area', 12, 6);
            $table->decimal('building_area', 12, 6);

            $table->integer('building_age');
            $table->string('house_layout');
            $table->string('connecting_road');
            $table->string('contracted_years');
            $table->dateTime('publish_date');

            $table->string('traffic');
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
        Schema::dropIfExists('lp_properties');
    }
}
