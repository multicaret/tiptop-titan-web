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
            $table->char('uuid', 10)->unique();
            $table->string('title'); //These are purposely not translatable!
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('link_type')->default(Slide::TYPE_EXTERNAL);
            $table->string('link_value')->nullable();
            $table->string('linkage')
                  ->nullable()
                  ->comment('The entity the deeplink will point to that has ID of link_value (i.e: Restaurant::class');
            $table->timestamp('begins_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM');
            $table->unsignedInteger('order_column')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('editor_id')->references('id')->on('users');
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
