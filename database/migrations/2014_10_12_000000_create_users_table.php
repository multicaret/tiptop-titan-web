<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first');
            $table->string('last')->nullable();
            $table->string('username', 60)->index()->unique();
            $table->string('email')->index()->unique()->nullable();
            $table->string('password')->nullable();
            $table->string('phone_country_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('bio')->nullable();
            $table->date('dob')->nullable();
            $table->unsignedTinyInteger('gender')->nullable();
            $table->decimal('wallet_reserved_total')->default(0);
            $table->decimal('wallet_free_total')->default(0);
            $table->unsignedBigInteger('profession_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable()->comment('Native language ID')->default(config('defaults.language.id'));
            $table->unsignedBigInteger('currency_id')->nullable()->default(config('defaults.currency.id'));
            $table->unsignedBigInteger('country_id')->nullable()->default(config('defaults.country.id'));
            $table->unsignedBigInteger('region_id')->nullable()->default(config('defaults.region.id'));
            $table->unsignedBigInteger('city_id')->nullable()->default(config('defaults.city.id'));
            $table->unsignedBigInteger('selected_address_id')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('avg_rating', 3)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(1);
            $table->unsignedInteger('total_number_of_orders')->default(0);
            $table->unsignedBigInteger('order_column')->nullable();
            $table->json('social_networks')->nullable();
            $table->json('settings')->comment('to handle all sort of settings including notification related such as is_notifiable by email or by push notifications ...etc');
            $table->unsignedTinyInteger('status')->default(1)->comment('0:incomplete, 1:draft, 2:published, 3:Inactive, 4..n:CUSTOM');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_logged_in_at')->nullable();
            $table->timestamp('last_logged_out_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->unique(['phone_country_code', 'phone_number']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
