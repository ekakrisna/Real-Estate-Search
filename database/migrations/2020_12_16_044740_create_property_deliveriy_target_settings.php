<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyDeliveriyTargetSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_delivery_target_settings', function (Blueprint $table) {
            $table->bigInteger('id', true);
			$table->bigInteger('property_deliveries_id')->nullable()->index('fk_property_delivery_target_settings_property_deliveries1_idx');
			$table->bigInteger('companies_id')->unsigned()->nullable()->index('fk_property_delivery_target_settings_companies1_idx');
			$table->bigInteger('company_users_id')->unsigned()->nullable()->index('fk_property_delivery_target_settings_company_users1_idx');
            $table->bigInteger('customers_id')->nullable()->index('fk_property_delivery_target_settings_property_customers1_idx');
            
            $table->foreign('property_deliveries_id', 'fk_property_delivery_target_settings_property_deliveries10')->references('id')->on('property_deliveries')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('companies_id', 'fk_property_delivery_target_settings_companies10')->references('id')->on('companies')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('company_users_id', 'fk_property_delivery_target_settings_company_users10')->references('id')->on('company_users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('customers_id', 'fk_property_delivery_target_settings_customers10')->references('id')->on('customers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_delivery_target_settings');
    }
}
