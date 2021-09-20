<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSendEmailToCustomerNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_news', function (Blueprint $table) {
            $table->boolean('is_send_email')->after('property_deliveries_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customer_news', function (Blueprint $table) {
            $table->dropColumn('is_send_email');
        });
    }
}
