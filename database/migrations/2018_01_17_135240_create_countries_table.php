<?php

use App\Models\Country;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('language_id')->nullable();
            $table->unsignedBigInteger('timezone_id')->nullable();
            $table->string('english_name');
            $table->char('alpha2_code', 2);
            $table->char('alpha3_code', 3);
            $table->unsignedSmallInteger('numeric_code');
            $table->string('phone_code', 8)->nullable();
            $table->unsignedInteger('order_column')->nullable();
            $table->unsignedTinyInteger('status')->default(Country::STATUS_DRAFT)->comment('1:draft, 2:active, 3:Inactive, 4..n:CUSTOM');
            $table->timestamps();

            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('set null');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('set null');
            $table->foreign('timezone_id')->references('id')->on('timezones')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
