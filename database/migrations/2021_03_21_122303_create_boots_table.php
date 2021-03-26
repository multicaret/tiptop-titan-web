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
            $table->unsignedTinyInteger('application_type')->default(\App\Models\Boot::TYPE_APPLICATION_CUSTOMER)->comment('1:customer, 2:restaurant, 3:driver');
            $table->string('platform_type')->comment('ios,android,other');
            $table->unsignedTinyInteger('update_method')->comment('0:disabled, 1:soft, 2:hard');
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
