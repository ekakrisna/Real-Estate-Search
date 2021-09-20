<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpdateLineGroupCitiesAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_line', function (Blueprint $table) {
            $table->integer('id', true)->unsigned();
            $table->string('group_character', 45);
        });

        Schema::table('cities_areas', function (Blueprint $table) {
            $table->string('display_name_kana', 45)->nullable();
            $table->integer('group_line_id')->unsigned()->nullable();
            $table->foreign('group_line_id')->references('id')->on('group_line')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('update_line_group_cities_area');
    }
}
