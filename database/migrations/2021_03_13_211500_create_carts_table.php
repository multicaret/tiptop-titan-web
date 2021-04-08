<?php

use App\Models\Cart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('chain_id')->index()->nullable();
            $table->unsignedBigInteger('branch_id')->index()->nullable();
            $table->unsignedDouble('total')->default(0);
            $table->unsignedDouble('without_discount_total')->default(0);
            $table->unsignedBigInteger('crm_id')->nullable();
            $table->unsignedBigInteger('crm_user_id')->nullable();
            $table->unsignedTinyInteger('status')->default(Cart::STATUS_IN_PROGRESS)->comment('0:In Progress, 1: Completed');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('carts');
    }
}
