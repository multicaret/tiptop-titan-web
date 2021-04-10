<?php

use App\Models\Search;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('searches', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            $table->string('term');
            $table->unsignedBigInteger('count')->default(1);
            $table->unsignedBigInteger('chain_id')->index()->nullable();
            $table->unsignedBigInteger('branch_id')->index()->nullable();
            $table->unsignedTinyInteger('type')->default(Search::CHANNEL_FOOD_OBJECT)->comment('1:Market, 2: Food');
            $table->timestamps();

            $table->unique(['locale', 'term', 'chain_id']);

            $table->foreign('chain_id')->references('id')->on('chains')->onDelete('cascade');
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
        Schema::dropIfExists('searches');
    }
}
