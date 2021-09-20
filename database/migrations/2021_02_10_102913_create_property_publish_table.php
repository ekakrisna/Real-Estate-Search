<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyPublishTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_publish', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('properties_id');
            $table->foreign('properties_id')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->string('publication_destination', 45)->nullable();
            $table->string('url', 200)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_publish');
    }
}
