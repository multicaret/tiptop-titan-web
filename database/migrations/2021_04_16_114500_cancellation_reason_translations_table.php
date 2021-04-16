<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CancellationReasonTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cancellation_reason_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cancellation_reason_id');
            $table->string('locale')->index();
            $table->string('reason')->nullable();
            $table->string('description')->nullable();

            $table->foreign('cancellation_reason_id')
                  ->references('id')
                  ->on('cancellation_reasons')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cancellation_reason_translations');
    }
}
