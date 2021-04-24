<?php

use App\Models\ProductOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->index();
            $table->boolean('is_based_on_ingredients')->default(false);
            $table->boolean('is_required')->default(false);
            // is_based_on_ingredients == true =>
            // 1.  Including hard coded.
            // 2.
            $table->unsignedTinyInteger('type')->default(ProductOption::TYPE_INCLUDING)
                  ->comment('
                    1: Including,
                    2: Excluding,
                  ');
            $table->unsignedInteger('max_number_of_selection')->nullable();
            $table->unsignedInteger('min_number_of_selection')->nullable();
            $table->unsignedTinyInteger('input_type')->default(ProductOption::INPUT_TYPE_PILL);
            $table->unsignedTinyInteger('selection_type')->default(ProductOption::SELECTION_TYPE_SINGLE_VALUE);
            $table->unsignedInteger('order_column')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('product_options');
    }
}
