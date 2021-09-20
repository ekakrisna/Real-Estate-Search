<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPropertiesIdToPropertyPublishingSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('property_publishing_settings', function (Blueprint $table) {
            $table->bigInteger('properties_id')->unsigned()->nullable()->index('fk_property_publishing_settings_properties1_idx');
            $table->foreign('properties_id', 'fk_property_publishing_settings_properties1')->references('id')->on('properties')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('property_publishing_settings', function (Blueprint $table) {
            $table->dropForeign('fk_property_publishing_settings_properties1');
            $table->dropColumn('properties_id');
        });
    }
}
