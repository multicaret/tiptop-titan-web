<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 10)->unique();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->index();
            $table->unsignedBigInteger('chain_id')->index();
            $table->unsignedBigInteger('branch_id')->index()->nullalbe();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedFloat('price')->nullable();
            $table->unsignedFloat('price_discount_amount')->nullable();
            $table->boolean('price_discount_by_percentage')->nullable()->comment('true: percentage, false: fixed amount');
            $table->unsignedBigInteger('quantity')->nullable();
            $table->string('sku')->nullable();
            $table->unsignedBigInteger('upc')->nullable();
            $table->boolean('is_storage_tracking_enabled')->nullable(true);
            $table->unsignedFloat('width')->nullable()->comment('x');
            $table->unsignedFloat('height')->nullable()->comment('y');
            $table->unsignedFloat('depth')->nullable()->comment('z');
            $table->unsignedFloat('weight')->nullable()->comment('w');
            $table->integer('minimum_orderable_quantity')->default(1)->nullable();
            $table->unsignedInteger('order_column')->nullable();
            $table->decimal('avg_rating', 3)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->integer('view_count')->default(1);
            $table->integer('status')->default(1)->nullable();
            $table->timestamp('price_discount_began_at')->nullable();
            $table->timestamp('price_discount_finished_at')->nullable();
            $table->timestamp('custom_banner_began_at')->nullable();
            $table->timestamp('custom_banner_ended_at')->nullable();
            $table->unsignedTinyInteger('on_mobile_grid_tile_weight')->default(3);
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chain_id')->references('id')->on('chains')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('taxonomies')->onDelete('cascade');
            $table->foreign('unit_id')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
