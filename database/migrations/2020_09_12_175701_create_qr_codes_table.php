<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->morphs('qr_codeable');
            $table->string('route');
            $table->json('route_params')->nullable();
            $table->boolean('is_external_route')->default(false);
            $table->string('forecolor')->default(config('defaults.colors.qr_code_forecolor'));
            $table->string('backcolor')->default(config('defaults.colors.qr_code_backcolor'));
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('qr_codes');
    }
}
