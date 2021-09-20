<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('publication_destination');
            $table->dropColumn('publication_destination_link');

            $table->bigInteger('minimum_price')->change();
            $table->bigInteger('maximum_price')->change();
        
            $table->Integer('property_scraping_type_id');
            $table->foreign('property_scraping_type_id')->references('id')->on('property_scraping_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            
            $table->Integer('property_convert_status_id');
            $table->foreign('property_convert_status_id')->references('id')->on('property_convert_status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
