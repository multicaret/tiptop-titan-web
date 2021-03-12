<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBarcodeProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barcode_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barcode_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->timestamps();

            $table->foreign('barcode_id')->references('id')->on('barcodes')->onDelete('cascade');
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
        Schema::dropIfExists('barcode_product');
    }
}
