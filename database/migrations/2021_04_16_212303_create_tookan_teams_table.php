<?php

use App\Models\TookanTeam;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTookanTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tookan_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->unsignedBigInteger('editor_id')->index();
            $table->string('tookan_team_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedTinyInteger('status')->default(TookanTeam::STATUS_DRAFT)->comment('1:draft, 2:active, 3:Inactive, 4..n:CUSTOM');

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
        Schema::dropIfExists('tookan_teams');
    }
}
