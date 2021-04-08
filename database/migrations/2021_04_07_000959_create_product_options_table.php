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

            // Todo:
//            $table->unsignedBigInteger('ingredient_id')->index()->nullable();
//            $table->foreign('ingredient_id')->references('id')->on('taxonomies')->onDelete('cascade');
            $table->unsignedTinyInteger('type')->default(ProductOption::TYPE_INCLUDING)
                  ->comment('
                    1: Including,
                    2: Excluding,
                  ');
//            $table->boolean('is_behaviour_method_excluding')->default(false);
            $table->unsignedInteger('max_number_of_selection')->nullable();
            $table->unsignedInteger('min_number_of_selection')->nullable();
            $table->unsignedTinyInteger('selection_type')->default(ProductOption::SELECTION_TYPE_SINGLE_VALUE);
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
