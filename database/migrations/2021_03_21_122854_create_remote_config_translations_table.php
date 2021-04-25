<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemoteConfigTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remote_config_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('remote_config_id');
            $table->string('title')->nullable();
            $table->json('data_translated')->nullable();
            $table->string('locale')->index();

            $table->unique(['remote_config_id', 'locale']);

            $table->foreign('remote_config_id')->references('id')->on('remote_configs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('remote_config_translations');
    }
}
