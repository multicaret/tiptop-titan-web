<?php

use App\Models\Order;
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
            $table->double('total')->default(0);
            $table->double('coupon_discount_amount')->default(0);
            $table->double('delivery_fee')->default(0);
            $table->double('grand_total')->default(0);
            $table->double('private_payment_method_commission')->default(0);
            $table->double('private_total')->default(0);
            $table->double('private_delivery_fee')->default(0);
            $table->double('private_grand_total')->default(0);
            $table->decimal('avg_rating', 3)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedTinyInteger('status')->default(Order::STATUS_DRAFT)
                  ->comment("
            0: Cancelled,
            1: Draft,
            6: Waiting Courier,
            10: Preparing,
            16: On the way,
            18: At the address,
            20: Delivered,
            ");
            $table->timestamps();
            $table->softDeletes();

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
