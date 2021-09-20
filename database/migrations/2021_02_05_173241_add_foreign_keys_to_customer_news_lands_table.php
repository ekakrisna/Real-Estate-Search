<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCustomerNewsLandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_news_lands', function (Blueprint $table) {
            $table->foreign('prefectures_id', 'customer_news_lands_prefectures1')->references('id')->on('prefectures');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_news_lands', function (Blueprint $table) {
            $table->dropForeign('customer_news_lands_prefectures1');
        });
    }
}
