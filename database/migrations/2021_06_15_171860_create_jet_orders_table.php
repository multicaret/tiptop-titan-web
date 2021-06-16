<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJetOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jet_orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code');
            $table->unsignedBigInteger('chain_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
         //   $table->unsignedBigInteger('payment_method_id')->index();
            $table->unsignedBigInteger('city_id');
            $table->string('destination_full_name');
            $table->string('destination_phone');
            $table->text('destination_address');
            $table->decimal('destination_latitude', 11, 8)->nullable();
            $table->decimal('destination_longitude', 11, 8)->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedDouble('total')->default(0);
            $table->unsignedDouble('delivery_fee')->default(0);
            $table->unsignedDouble('grand_total')->default(0);
            $table->unsignedDouble('grand_total_before_agent_manipulation')->default(0);

            $table->unsignedBigInteger('cancellation_reason_id')->nullable();
            $table->string('cancellation_reason_note')->nullable();

            // Todo: MK check it please. Used it to store old data
            $table->unsignedBigInteger('delivery_time')->nullable()->default(0);

            $table->text('restaurant_notes')->nullable();
            $table->text('private_notes')->nullable()
                  ->comment('This column is generic, for now it has the \'discount_method_id\' for orders with coupons from the old DB');

            $table->text('client_notes')->nullable();
            $table->unsignedTinyInteger('status')->default(\App\Models\JetOrder::STATUS_NEW)
                  ->comment('
                    0: Cancelled,
                    1: Draft,
                    6: Waiting Courier,
                    16: On the way,
                    18: At the address,
                    20: Delivered,
                  ');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['reference_code']);

            $table->foreign('chain_id')->references('id')->on('chains')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
       //     $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('driver_id')->references('id')->on('users');
            $table->foreign('cancellation_reason_id')->references('id')->on('taxonomies')->onDelete('set null');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jet_orders');
    }
}
