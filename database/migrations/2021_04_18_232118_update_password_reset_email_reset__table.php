<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePasswordResetEmailResetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers_reset_password', function (Blueprint $table) {                    
            $table->renameColumn('text', 'token');
        });
        Schema::table('customers_reset_emails', function (Blueprint $table) {                    
            $table->renameColumn('text', 'token');
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
