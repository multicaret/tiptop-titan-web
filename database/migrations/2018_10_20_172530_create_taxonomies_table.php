<?php

use App\Models\Taxonomy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 10)->unique();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id');
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->unsignedBigInteger('chain_id')->index()->nullable();
            $table->unsignedBigInteger('branch_id')->index()->nullable();
            $table->unsignedBigInteger('ingredient_category_id')->nullable();
            $table->unsignedTinyInteger('type')->default(1)->comment('1:Category, 2: Tag, 3..n: CUSTOM');
            $table->string('icon')->nullable();
            $table->unsignedBigInteger('view_count')->default(1);
            $table->integer('left');
            $table->integer('right');
            $table->integer('depth')->nullable();
            $table->decimal('step')->nullable();
            $table->unsignedBigInteger('order_column')->nullable();
            $table->unsignedBigInteger('cloned_from_taxonomy_id')->nullable();
            $table->unsignedTinyInteger('status')->default(Taxonomy::STATUS_DRAFT)->comment('1:draft, 2:active, 3:Inactive, 4..n:CUSTOM');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('editor_id')->references('id')->on('users');
            $table->foreign('parent_id')->references('id')->on('taxonomies');
            $table->foreign('ingredient_category_id')->references('id')->on('taxonomies');
            $table->foreign('cloned_from_taxonomy_id')->references('id')->on('taxonomies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomies');
    }
}
