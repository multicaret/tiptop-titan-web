<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBootTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boot_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('boot_id');
            $table->string('title')->nullable();
            $table->json('data_translated')->nullable();
            $table->string('locale')->index();

            $table->unique(['boot_id', 'locale']);

            $table->foreign('boot_id')->references('id')->on('boots')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boot_translations');
    }
}
