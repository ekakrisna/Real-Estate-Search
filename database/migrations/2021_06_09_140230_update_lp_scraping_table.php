<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLpScrapingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('scraping_publish_copy1', function (Blueprint $table) {
            $table->string('url', 200)->change();
        });
        */
        Schema::table('lp_scrapings', function (Blueprint $table) {
            $table->string('location', 200)->change();
            $table->string('connecting_road', 100)->change();
        });
        Schema::table('lp_scraping_file_upload_histories', function (Blueprint $table) {
            $table->string('file_name', 200)->change();
        });
        Schema::table('lp_properties', function (Blueprint $table) {
            $table->string('house_layout', 100)->change();
            $table->string('connecting_road', 100)->change();
            $table->string('contracted_years', 100)->change();
        });
        Schema::table('lp_property_statuses', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('label', 100)->change();
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
