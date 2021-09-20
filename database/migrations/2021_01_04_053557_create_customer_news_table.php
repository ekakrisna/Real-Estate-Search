<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_news', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customers_id');
            $table->foreign('customers_id')
            ->references('id')->on('customers')
            ->onDelete('cascade');

            $table->integer('type');
            $table->boolean('is_show');
            $table->text('location')->nullable();
            
            $table->bigInteger('property_deliveries_id')->nullable();
            $table->foreign('property_deliveries_id')
            ->references('id')->on('property_deliveries')
            ->onDelete('cascade');
            
            $table->unsignedBigInteger('customer_news_lands_id')->nullable();
            $table->foreign('customer_news_lands_id')->references('id')->on('customer_news_lands')->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_news');
    }
}
