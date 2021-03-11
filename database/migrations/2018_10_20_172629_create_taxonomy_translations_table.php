<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomyTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('taxonomy_id');
            $table->string('locale')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('is_auto_inserted')->default(0);

            $table->unique(['taxonomy_id', 'locale']);
            $table->foreign('taxonomy_id')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomy_translations');
    }
}
