<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCustomersColumn extends Migration
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
			$table->dropForeign('fk_customers_list_consider_amount1');
			$table->dropForeign('fk_customers_list_consider_amount2');
			$table->dropForeign('fk_customers_list_consider_amount3');
			$table->dropForeign('fk_customers_list_consider_amount4');
            $table->dropForeign('fk_customers_list_land_area1');
            $table->dropForeign('fk_customers_list_land_area2');

            $table->dropIndex('fk_customers_list_consider_amount1_idx');
            $table->dropIndex('fk_customers_list_consider_amount2_idx');
            $table->dropIndex('fk_customers_list_consider_amount3_idx');
            $table->dropIndex('fk_customers_list_consider_amount4_idx');
            $table->dropIndex('fk_customers_list_land_area1_idx');
            $table->dropIndex('fk_customers_list_land_area2_idx');
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
