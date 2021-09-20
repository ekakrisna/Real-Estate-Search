<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePropertyPublishingSettingsIdToPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign('fk_properties_property_publishing_settings1');
            $table->dropColumn('property_publishing_settings_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->bigInteger('property_publishing_settings_id')->nullable()->index('fk_properties_property_publishing_settings1_idx');
            $table->foreign('property_publishing_settings_id', 'fk_properties_property_publishing_settings1')->references('id')->on('property_publishing_settings')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }
}
