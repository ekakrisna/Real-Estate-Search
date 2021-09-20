<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCustomersColumnRename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function(Blueprint $table)
		{   
            $table->renameColumn('minimum_price_id', 'minimum_price');
            $table->renameColumn('maximum_price_id', 'maximum_price');
            $table->renameColumn('minimum_price_land_area_id', 'minimum_price_land_area');
            $table->renameColumn('maximum_price_land_area_id', 'maximum_price_land_area');
            $table->renameColumn('minimum_land_area_id', 'minimum_land_area');
            $table->renameColumn('maximum_land_area_id', 'maximum_land_area'); 
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
