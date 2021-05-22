<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZohoBooksInfoToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            $table->string('zoho_books_id')->nullable();
            $table->string('zoho_books_account_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branches', function (Blueprint $table) {
            //
        });
    }
}
