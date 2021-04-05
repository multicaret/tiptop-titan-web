<?php

use App\Models\Chain;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chains', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 10)->unique();
            $table->unsignedBigInteger('creator_id')->index();
            $table->unsignedBigInteger('editor_id');
            $table->unsignedBigInteger('region_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable()->default(config('defaults.currency.id'));
            $table->unsignedTinyInteger('type')->default(Chain::TYPE_FOOD_OBJECT)->comment('1:Market, 2: Food');
            $table->string('primary_phone_number')->nullable();
            $table->string('secondary_phone_number')->nullable();
            $table->string('whatsapp_phone_number')->nullable();
            $table->string('primary_color')->default(config('defaults.colors.chain_primary_color'));
            $table->string('secondary_color')->default(config('defaults.colors.chain_secondary_color'));
            $table->unsignedTinyInteger('number_of_items_on_mobile_grid_view')->default(3);
            $table->decimal('avg_rating', 3)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(1);
            $table->unsignedBigInteger('order_column')->nullable();
            $table->unsignedTinyInteger('status')->default(Chain::STATUS_DRAFT)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('editor_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chains');
    }
}
