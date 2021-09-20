<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerNewsLandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_news_lands', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedInteger('prefectures_id')->index('customer_news_lands_prefectures1_idx');            
            
            $table->unsignedBigInteger('cities_id');
            $table->foreign('cities_id')->references('id')->on('cities')->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->unsignedBigInteger('towns_id');
            $table->foreign('towns_id')->references('id')->on('towns')->onUpdate('NO ACTION')->onDelete('NO ACTION');           

            $table->unsignedBigInteger('properties_id');
            $table->foreign('properties_id')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');                    

            $table->bigInteger('creaping_histories_id');

            $table->boolean('is_created_news');

            $table->dateTime('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_news_lands');
    }
}
