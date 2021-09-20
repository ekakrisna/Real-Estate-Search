<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_news', function (Blueprint $table) {
            $table->dropForeign('customer_news_customer_news_lands_id_foreign');
            $table->dropColumn('customer_news_lands_id');  
        });

        Schema::dropIfExists('customer_news_lands');

        Schema::create('customer_news_property', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('customer_news_id')->unsigned()->nullable();
            $table->foreign('customer_news_id')->references('id')->on('customer_news')->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->bigInteger('property_id')->unsigned()->nullable();
            $table->foreign('property_id')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->timestamp('created_at', 0)->nullable();
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
