<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBeforeLoginCustomerLogActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('before_login_customer_log_activities', function (Blueprint $table) {
            $table->bigInteger('id', true)->unsigned();
            $table->bigInteger('properties_id')->unsigned()->nullable();
            $table->string('mac_address', 20);
            $table->dateTime('access_time');
            $table->bigInteger('before_login_customers_id')->unsigned()->nullable();
            
            $table->foreign('properties_id')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            
            $table->foreign('before_login_customers_id', 'blc_id_foreign')->references('id')->on('before_login_customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('before_login_customer_log_activities');
    }
}
