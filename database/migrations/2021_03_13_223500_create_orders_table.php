<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_code')->unique();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('chain_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->unsignedBigInteger('basket_id')->index();
            $table->unsignedBigInteger('payment_method_id')->index();
            $table->unsignedBigInteger('address_id')->index();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('previous_order_id')->nullable();
            $table->unsignedFloat('total')->default(0);
            $table->unsignedFloat('coupon_discount_amount')->default(0);
            $table->unsignedFloat('delivery_fee')->default(0);
            $table->unsignedFloat('grand_total')->default(0);
            $table->unsignedFloat('private_payment_method_commission')->default(0);
            $table->unsignedFloat('private_total')->default(0);
            $table->unsignedFloat('private_delivery_fee')->default(0);
            $table->unsignedFloat('private_grand_total')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('payment_completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('chain_id')->references('id')->on('chains');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('basket_id')->references('id')->on('baskets');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('previous_order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
