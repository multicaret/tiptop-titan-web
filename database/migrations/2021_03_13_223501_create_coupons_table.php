<?php

use App\Models\Coupon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id');
            $table->unsignedBigInteger('currency_id')->default(config('defaults.currency.id'));
            $table->unsignedTinyInteger('type')->default(Coupon::TYPE_GROCERY_OBJECT)->comment('1:Market, 2: Food');
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedDouble('discount_amount')->nullable();
            $table->boolean('discount_by_percentage')->nullable()->comment('true: percentage, false: fixed amount');
            $table->unsignedDouble('max_allowed_discount_amount')->default(0);
            $table->unsignedDouble('min_cart_value_allowed')->default(0);
            $table->boolean('has_free_delivery')->default(false);
            $table->unsignedInteger('max_usable_count')->default(1);
            $table->unsignedInteger('total_redeemed_count')->default(0);
            $table->unsignedInteger('max_usable_count_by_user')->default(1);
            $table->unsignedDouble('money_redeemed_so_far')->default(0);
            $table->timestamp('expired_at')->nullable();
            $table->string('redeem_code');
            $table->unsignedTinyInteger('status')->default(Coupon::STATUS_DRAFT)->comment('1:draft, 2:active, 3:Inactive, 4..n:CUSTOM');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('editor_id')->references('id')->on('users');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('coupon_id')->references('id')->on('coupons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('coupon_id');
        });
        Schema::dropIfExists('coupons');
    }
}
