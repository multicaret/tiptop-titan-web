<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaDataTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_data_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meta_data_id')->unsigned();
            $table->string('locale');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_type')->nullable();
            $table->text('twitter_card')->nullable();
            $table->text('twitter_title')->nullable();
            $table->text('twitter_description')->nullable();

            $table->unique(['meta_data_id', 'locale']);
            $table->foreign('meta_data_id')->references('id')->on('meta_data')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meta_data_translations');
    }
}
