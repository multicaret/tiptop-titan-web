<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJetDeliveryDetailsToBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branches', function (Blueprint $table) {
            // TipTop Jet Delivery
            $table->boolean('has_jet_delivery')->default(false);
            $table->unsignedDouble('jet_minimum_order')->default(0);
            $table->unsignedDouble('jet_fixed_delivery_fee')->default(0);
            $table->unsignedDouble('jet_delivery_commission_rate')->default(0);
            $table->unsignedInteger('jet_extra_delivery_fee_per_km')->default(0);
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
