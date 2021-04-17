<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SearchTaggableTable extends Migration
{
    public function up()
    {
        Schema::create('search_taggables', function (Blueprint $table) {
            $table->id();
            $table->morphs('search_taggable');
            $table->unsignedBigInteger('taxonomy_id')->index();
            $table->unsignedBigInteger('order_column')->nullable();
            $table->timestamps();

            $table->foreign('taxonomy_id')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('search_taggables');
    }
}
