<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id');
            $table->nullableMorphs('contactable');
            $table->unsignedBigInteger('country_id')->index()->nullable();
            $table->unsignedBigInteger('region_id')->index()->nullable();
            $table->unsignedBigInteger('city_id')->index()->nullable();
            $table->string('alias')->nullable();
            $table->string('name')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('building', 100)->nullable();
            $table->string('floor', 50)->nullable();
            $table->string('apartment', 50)->nullable();
            $table->string('postcode', 12)->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('notes')->nullable();
            $table->text('phones')->nullable();
            $table->text('mobiles')->nullable();
            $table->text('emails')->nullable();
            $table->text('social_media')->nullable();
            $table->string('website')->nullable();
            $table->string('position')->nullable();
            $table->string('company')->nullable();
            $table->string('vat')->nullable()->comment('value added tax');
            $table->string('vat_office')->nullable();
            $table->unsignedTinyInteger('type')->default(1)->comment('1: Address, 2: Contact');
            $table->unsignedTinyInteger('status')->default(1)->comment('0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('editor_id')->references('id')->on('users');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
