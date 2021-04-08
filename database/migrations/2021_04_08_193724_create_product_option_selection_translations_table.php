<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionSelectionTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_option_selection_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_option_selection_id');
            $table->string('title')->nullable();
            $table->string('locale')->index();

            $table->unique(['product_option_selection_id', 'locale'], 'selection_translations_unique');
            $table->timestamps();

            $table->foreign('product_option_selection_id',
                'product_selection_translation_fk')->references('id')->on('product_option_selections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_option_selection_translations');
    }
}
