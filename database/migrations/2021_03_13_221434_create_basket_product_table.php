<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basket_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('basket_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->json('product_object');
            $table->timestamps();

            $table->foreign('basket_id')->references('id')->on('baskets')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('basket_product');
    }
}
