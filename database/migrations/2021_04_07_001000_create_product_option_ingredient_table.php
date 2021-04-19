<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionIngredientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_ingredient', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_option_id')->index();
            $table->unsignedBigInteger('ingredient_id')->index();
            $table->unsignedDouble('price')->default(0);
            $table->timestamps();

            $table->foreign('product_option_id')->references('id')->on('product_options')->onDelete('cascade');
            $table->foreign('ingredient_id')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_option_ingredient');
    }
}
