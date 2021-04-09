<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionSelectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_selections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_option_id')->index();
            $table->unsignedBigInteger('product_id')->index()->comment('this is a helper');
            $table->unsignedDouble('price')->default(0);
            $table->timestamps();

            $table->foreign('product_option_id')->references('id')->on('product_options')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_option_selections');
    }
}
