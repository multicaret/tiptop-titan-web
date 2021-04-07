<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductOptionsTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_options_id');
            $table->string('group_title')->nullable();
            $table->string('option_title')->nullable();
            $table->string('locale')->index();

            $table->unique(['product_options_id', 'locale']);
            $table->timestamps();

            $table->foreign('product_options_id')->references('id')->on('product_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_options_translations');
    }
}
