<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCustomerNewLandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_news_lands', function (Blueprint $table) {
            $table->renameColumn('creaping_histories_id', 'scraping_histories_id');
        });
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign('properties_town_id_foreign');
            $table->dropColumn('town_id');
            $table->dropColumn('is_conversion');
        });
        Schema::table('scrapings', function (Blueprint $table) {
            $table->dropForeign('scrapings_town_id_foreign');
            $table->dropColumn('town_id');
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
