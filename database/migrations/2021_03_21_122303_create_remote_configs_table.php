<?php

use App\Models\RemoteConfig;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemoteConfigsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remote_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('build_number');
            $table->unsignedTinyInteger('application_type')->default(RemoteConfig::TYPE_APPLICATION_CUSTOMER)->comment('1:customer, 2:restaurant, 3:driver');
            $table->string('platform_type')->comment('ios,android,other');
            $table->unsignedTinyInteger('update_method')->comment('0:disabled, 1:soft, 2:hard');
            $table->json('data')->nullable();


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
        Schema::dropIfExists('remote_configs');
    }
}
