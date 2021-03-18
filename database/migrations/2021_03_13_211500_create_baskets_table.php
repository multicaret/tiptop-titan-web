<?php

use App\Models\Basket;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('chain_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->double('total')->default(0);
            $table->double('without_discount_total')->default(0);
            $table->unsignedBigInteger('crm_id')->nullable();
            $table->unsignedBigInteger('crm_user_id')->nullable();
            $table->unsignedTinyInteger('status')->default(Basket::STATUS_IN_PROGRESS)->comment('0:In Progress, 1: Completed');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('chain_id')->references('id')->on('chains');
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baskets');
    }
}
