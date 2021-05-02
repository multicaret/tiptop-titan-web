<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderDailyReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_daily_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id')->index();
            $table->date('day');
            $table->boolean('is_weekend')->default(false);
            $table->unsignedInteger('total_grocery_orders_count');
            $table->unsignedInteger('total_food_orders_count');
            $table->unsignedInteger('total_orders_count');
            $table->unsignedInteger('total_delivered_grocery_orders_count');
            $table->unsignedInteger('total_delivered_food_orders_count');
            $table->unsignedInteger('total_delivered_orders_count');
            $table->unsignedInteger('average_delivery_time');
            $table->unsignedDouble('average_orders_value');
            $table->unsignedInteger('orders_count_between_09_12')->default(0);
            $table->unsignedInteger('orders_count_between_12_15')->default(0);
            $table->unsignedInteger('orders_count_between_15_18')->default(0);
            $table->unsignedInteger('orders_count_between_18_21')->default(0);
            $table->unsignedInteger('orders_count_between_21_00')->default(0);
            $table->unsignedInteger('orders_count_between_00_03')->default(0);
            $table->unsignedInteger('orders_count_between_03_09')->default(0);
            $table->unsignedInteger('registered_users_count')->default(0);
            $table->unsignedInteger('ordered_users_count')->default(0);
            $table->unsignedInteger('ios_devices_count')->default(0);
            $table->unsignedInteger('android_devices_count')->default(0);
            $table->unsignedInteger('other_devices_count')->default(0);
            $table->unsignedInteger('total_mobile_users_count')->default(0);
            $table->unsignedInteger('total_web_users_count')->default(0);
            $table->boolean('is_peak_of_this_month')->default(false);
            $table->boolean('is_nadir_of_this_month')->default(false);
            $table->boolean('is_peak_of_this_quarter')->default(false);
            $table->boolean('is_nadir_of_this_quarter')->default(false);
            $table->boolean('is_peak_of_this_year')->default(false);
            $table->boolean('is_nadir_of_this_year')->default(false);
            $table->timestamps();

            $table->unique(['region_id', 'day']);
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_daily_reports');
    }
}
