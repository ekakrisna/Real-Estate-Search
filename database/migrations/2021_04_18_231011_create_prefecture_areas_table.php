<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrefectureAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prefecture_areas', function (Blueprint $table) {
			$table->bigInteger('id',true)->unsigned();
			$table->boolean('is_all_show');
			$table->string('display_name', 60);
            $table->integer('prefecture_id')->unsigned()->nullable();
            $table->foreign('prefecture_id')->references('id')->on('prefectures')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prefecture_areas');
    }
}
