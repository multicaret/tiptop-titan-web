<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTookansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tookans', function (Blueprint $table) {
            $table->id();
            $table->morphs('tookanable');
            $table->string('tookan_id')->nullable();
            $table->string('job_pickup_id');
            $table->string('job_delivery_id');
            $table->string('delivery_tracking_link')->nullable();
            $table->string('pickup_tracking_link')->nullable();
            $table->string('job_hash')->nullable();
            $table->string('job_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tookans');
    }
}
