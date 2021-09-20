<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLpPropertyLogActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lp_property_log_activities', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('lp_properties_id')->unsigned();
            $table->foreign('lp_properties_id')->references('id')->on('lp_properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->text('before_update_text');
            $table->text('after_update_text');
            $table->dateTime('created_at');
            $table->integer('lp_property_scraping_types_id')->unsigned();
            $table->foreign('lp_property_scraping_types_id')->references('id')->on('lp_property_scraping_types')->onUpdate('NO ACTION')->onDelete('NO ACTION');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lp_property_log_activities');
    }
}
