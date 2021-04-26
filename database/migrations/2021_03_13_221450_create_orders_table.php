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
            $table->unsignedBigInteger('cart_id')->nullable()->index();
            $table->unsignedBigInteger('payment_method_id')->index();
            $table->unsignedBigInteger('address_id')->index();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('city_id');
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('previous_order_id')->nullable();
            $table->unsignedTinyInteger('type')->comment('1:Market, 2: Food');
            $table->unsignedDouble('total')->default(0);
            $table->unsignedDouble('coupon_discount_amount')->default(0);
            $table->unsignedDouble('delivery_fee')->default(0);
            $table->unsignedDouble('grand_total')->default(0);
            $table->unsignedDouble('grand_total_before_agent_manipulation')->default(0);
            $table->unsignedDouble('private_payment_method_commission')->default(0);
            $table->unsignedDouble('private_total')->default(0);
            $table->unsignedDouble('private_delivery_fee')->default(0);
            $table->unsignedDouble('private_grand_total')->default(0);
            $table->boolean('is_delivery_by_tiptop')->default(1);

            // Rating Related
            $table->decimal('branch_rating_value', 3)->nullable();
            $table->timestamp('rated_at')->nullable();
            $table->text('rating_comment')->nullable();
            // Rating Related - For Grocery Only
            $table->unsignedBigInteger('rating_issue_id')->nullable();
            // Rating Related - For Food Only
            $table->boolean('has_good_food_quality_rating')->nullable();
            $table->boolean('has_good_packaging_quality_rating')->nullable();
            $table->boolean('has_good_order_accuracy_rating')->nullable();
            $table->decimal('driver_rating_value', 3)->nullable();
            $table->text('driver_rating_comment')->nullable();
            $table->timestamp('driver_rated_at')->nullable();

            $table->unsignedBigInteger('cancellation_reason_id')->nullable();
            $table->string('cancellation_reason_note')->nullable();

            // Todo: MK check it please. Used it to store old data
            $table->unsignedBigInteger('delivery_time')->nullable()->default(0);
            $table->unsignedDouble('tiptop_share_result')->default(0);
            $table->unsignedDouble('tiptop_share_percentage')->default(0)->comment('is tiptop_share_percentage, which is taken directly from commission column in Branch');
            $table->unsignedDouble('restaurant_share_result')->default(0);
            $table->string('agent_device')->nullable();
            $table->string('agent_os')->nullable();
            $table->text('restaurant_notes')->nullable();
            $table->text('private_notes')->nullable()
                  ->comment('This column is generic, for now it has the \'discount_method_id\' for orders with coupons from the old DB');

            $table->timestamp('completed_at')->nullable();
            $table->text('customer_notes')->nullable();
            $table->unsignedTinyInteger('status')->default(Order::STATUS_DRAFT)
                  ->comment('
                    0: Cancelled,
                    1: Draft,
                    6: Waiting Courier,
                    10: Preparing,
                    16: On the way,
                    18: At the address,
                    20: Delivered,
                  ');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('chain_id')->references('id')->on('chains')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('address_id')->references('id')->on('locations');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('driver_id')->references('id')->on('users');
            $table->foreign('previous_order_id')->references('id')->on('orders');
            $table->foreign('rating_issue_id')->references('id')->on('taxonomies')->onDelete('set null');
            $table->foreign('cancellation_reason_id')->references('id')->on('taxonomies')->onDelete('set null');
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
