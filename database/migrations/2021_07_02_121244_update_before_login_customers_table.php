<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBeforeLoginCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('before_login_customers', function (Blueprint $table) {
            $table->renameColumn('mac_address', 'uuid');            
        });
        Schema::table('before_login_customer_log_activities', function (Blueprint $table) {
            $table->renameColumn('mac_address', 'uuid');            
        });        
        Schema::table('before_login_customers', function (Blueprint $table) {
            $table->string('uuid')->change();
        });
        Schema::table('before_login_customer_log_activities', function (Blueprint $table) {
            $table->string('uuid')->change();
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
