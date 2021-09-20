<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertyCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lp_property_categories', function(Blueprint $table)
		{
			$table->unsignedInteger('id')->primary();
			$table->string('display_name',100);
		});

        Schema::table('lp_scrapings', function(Blueprint $table)
		{
			$table->unsignedInteger('lp_property_category_id')->nullable();;
            $table->foreign('lp_property_category_id')->references('id')->on('lp_property_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');

		});

        Schema::table('lp_properties', function(Blueprint $table)
		{
			$table->unsignedInteger('lp_property_category_id')->nullable();;
            $table->foreign('lp_property_category_id')->references('id')->on('lp_property_categories')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
