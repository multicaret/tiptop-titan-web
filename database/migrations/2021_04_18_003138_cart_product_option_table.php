<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CartProductOptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_product_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cart_product_id')->index();
            $table->unsignedBigInteger('product_option_id')->index()->nullable();
            $table->json('product_option_object')->nullable();
            $table->timestamps();

            $table->foreign('cart_product_id')
                  ->references('id')
                  ->on('cart_product')
                  ->onDelete('cascade');

            $table->foreign('product_option_id')
                  ->references('id')
                  ->on('product_options')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_product_options');
    }
}
