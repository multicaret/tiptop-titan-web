<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChainTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chain_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chain_id');
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('locale')->index();

            $table->unique(['chain_id', 'locale']);
            $table->foreign('chain_id')->references('id')->on('chains')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chain_translations');
    }
}
