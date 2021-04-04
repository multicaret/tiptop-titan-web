<?php

use App\Models\Comment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedTinyInteger('type')->default(1)->comment('1:Comment, 2: Review, 3..n: CUSTOM');
            $table->text('content')->nullable();
            $table->morphs('commentable');
            $table->integer('left');
            $table->integer('right');
            $table->integer('depth')->nullable();
            $table->integer('votes')->default(0);
            $table->boolean('status')->default(Comment::STATUS_SHOWN)->comment('1:shown, 2:reported');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
