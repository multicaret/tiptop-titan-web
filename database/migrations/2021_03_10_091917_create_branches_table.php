<?php

use App\Models\Branch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 10)->unique();
            $table->unsignedBigInteger('chain_id')->index();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            // TipTop Delivery
            $table->boolean('has_tip_top_delivery')->default(true);
            $table->unsignedDouble('minimum_order')->default(0); // 50
            $table->unsignedDouble('under_minimum_order_delivery_fee')->default(0); // 10
            $table->unsignedDouble('fixed_delivery_fee')->default(0); // 5
            $table->unsignedInteger('min_delivery_minutes')->default(20);
            $table->unsignedInteger('max_delivery_minutes')->default(30);
            $table->unsignedInteger('free_delivery_threshold')->default(0);
            $table->unsignedInteger('extra_delivery_fee_per_km')->default(0);
            // Restaurant Delivery
            $table->boolean('has_restaurant_delivery')->default(false);
            $table->unsignedDouble('restaurant_minimum_order')->default(0);
            $table->unsignedDouble('restaurant_under_minimum_order_delivery_fee')->default(0);
            $table->unsignedDouble('restaurant_fixed_delivery_fee')->default(0);
            $table->unsignedInteger('restaurant_min_delivery_minutes')->default(20);
            $table->unsignedInteger('restaurant_max_delivery_minutes')->default(30);
            $table->unsignedInteger('restaurant_free_delivery_threshold')->default(0);
            $table->unsignedInteger('restaurant_extra_delivery_fee_per_km')->default(0);

            $table->unsignedInteger('management_commission_rate')->default(0)->comment('0 means there is no commission atall');
            $table->boolean('is_open_now')->default(true);

            $table->string('primary_phone_number')->nullable();
            $table->string('secondary_phone_number')->nullable();
            $table->string('whatsapp_phone_number')->nullable();
            $table->unsignedBigInteger('order_column')->nullable();
            $table->unsignedTinyInteger('type')->default(Branch::CHANNEL_FOOD_OBJECT)->comment('1:Market, 2: Food');
            $table->text('full_address')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('avg_rating', 3)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(1);
            $table->unsignedTinyInteger('status')->default(Branch::STATUS_DRAFT)->comment('1:draft, 2:active, 3:Inactive, 4..n:CUSTOM');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('featured_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            $table->foreign('chain_id')->references('id')->on('chains')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branches');
    }
}
