<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 10)->unique();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedTinyInteger('type')->default(1)->comment('1:Article, 2:Page, 3:Testimonial, 4..n: CUSTOM');
            $table->decimal('avg_rating', 3)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->integer('view_count')->default(1);
            $table->unsignedInteger('order_column')->nullable();
            $table->unsignedTinyInteger('status')->default(1)->comment('0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('taxonomies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
