<?php

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
            $table->float('minimum_order')->default(0); // 50
            $table->float('under_minimum_order_delivery_fee')->default(0); // 10
            $table->float('fixed_delivery_fee')->default(0); // 5
            $table->string('primary_phone_number')->nullable();
            $table->string('secondary_phone_number')->nullable();
            $table->string('whatsapp_phone_number')->nullable();
            $table->unsignedInteger('order_column')->nullable();
            $table->unsignedTinyInteger('type')->default(\App\Models\Branch::TYPE_FOOD_BRANCH)->comment('1:Market, 2: Food');
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('avg_rating', 3)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->integer('view_count')->default(1);
            $table->integer('status')->default(1)->nullable();
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
