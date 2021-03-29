<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_branch', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('taxonomies')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_branch');
    }
}
