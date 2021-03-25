<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlideTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slide_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slide_id');
            $table->text('alt_tag');
            $table->string('locale')->index();

            $table->unique(['slide_id', 'locale']);

            $table->foreign('slide_id')->references('id')->on('slides')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slide_translations');
    }
}
