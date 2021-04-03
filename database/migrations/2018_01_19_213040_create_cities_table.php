<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->index();
            $table->unsignedBigInteger('region_id')->index()->nullable();
            $table->unsignedBigInteger('timezone_id')->nullable();
            $table->string('english_name');
            $table->unsignedBigInteger('population')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->unsignedBigInteger('order_column')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM');
            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('timezone_id')->references('id')->on('timezones')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
