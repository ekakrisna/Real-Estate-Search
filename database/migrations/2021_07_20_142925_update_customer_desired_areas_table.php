<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomerDesiredAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('cities_areas', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->string('display_name');
            $table->bigInteger('cities_id')->unsigned()->nullable();
            $table->foreign('cities_id')->references('id')->on('cities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('customer_desired_areas', function (Blueprint $table) {
            $table->dropForeign('fk_customer_desired_area_towns1');
            $table->dropColumn('towns_id');
            $table->bigInteger('cities_area_id')->unsigned()->nullable();
            $table->foreign('cities_area_id')->references('id')->on('cities_areas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });

        Schema::table('towns', function (Blueprint $table) {
            $table->string('name_kana', 50);
            $table->bigInteger('cities_area_id')->unsigned()->nullable();
            $table->foreign('cities_area_id')->references('id')->on('cities_areas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
