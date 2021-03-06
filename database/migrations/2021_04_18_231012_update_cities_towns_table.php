<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCitiesTownsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {                         
            $table->bigInteger('prefecture_area_id')->nullable()->unsigned();
            $table->foreign('prefecture_area_id')->references('id')->on('prefecture_areas')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
        Schema::table('cities', function (Blueprint $table) {    
            $table->dropForeign('cities_prefecture_area_id_foreign');              
            $table->dropColumn('prefecture_area_id');
        });
    }
}
