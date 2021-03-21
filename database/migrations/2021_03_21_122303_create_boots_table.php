<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBootsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('build_number');
            $table->unsignedTinyInteger('application_type')->default(0)->comment('0:customer, 1:restaurant, 2:driver');
            $table->unsignedTinyInteger('platform_type')->comment('0:ios, 1:android, 3..n:CUSTOM');
            $table->unsignedTinyInteger('update_method')->comment('0:disabled, 1:soft, 1:hard');
            $table->json('data')->nullable();


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
        Schema::dropIfExists('boots');
    }
}
