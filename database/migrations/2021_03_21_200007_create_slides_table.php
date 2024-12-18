<?php

use App\Models\Slide;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->char('uuid', 10)->unique();
            $table->string('title'); //These are purposely not translatable!
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('link_type')->default(Slide::LINK_TYPE_EXTERNAL);
            $table->string('link_value')->nullable();
            $table->string('linkage')
                  ->nullable()
                  ->comment('The entity the deeplink will point to that has ID of link_value (i.e: Restaurant::class');
            $table->timestamp('begins_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedTinyInteger('channel')->default(Slide::CHANNEL_FOOD_AND_GROCERY_OBJECT)->comment('8:food and grocery, 9:grocery, 10:food');
            $table->unsignedInteger('order_column')->nullable();
            $table->unsignedTinyInteger('status')->default(Slide::STATUS_DRAFT)->comment('1:draft, 2:active, 3:Inactive, 4..n:CUSTOM');
            $table->unsignedTinyInteger('has_been_authenticated')->default(Slide::TARGET_EVERYONE)->comment('1:TARGET_LOGGED_IN, 2:TARGET_GUEST, 3:TARGET_EVERYONE');

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('editor_id')->references('id')->on('users');
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
        Schema::dropIfExists('slides');
    }
}
