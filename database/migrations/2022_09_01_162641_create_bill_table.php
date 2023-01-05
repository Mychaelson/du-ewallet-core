<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment.bill', function (Blueprint $table) {
            $table->id();
            $table->integer('user');
            $table->integer('merchant');
            $table->integer('status');
            $table->string('description', 50);
            $table->string('invoice', 50);
            $table->string('callback', 64);
            $table->string('currency', 50);
            $table->double('amount', 22, 2);
            $table->integer('paid');
            $table->double('cashback', 22, 2);
            $table->string('wallet', 50);
            $table->string('reason', 50);
            $table->string('pushed', 50)->nullable();
            $table->string('refund', 50)->nullable();
            $table->string('payment_service', 100);
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_data', 1050)->nullable();
            $table->json('bill_data', 1050)->nullable();
            $table->integer('reff_method_id')->nullable();
            $table->string('expires', 50);
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
        Schema::dropIfExists('payment.bill');
    }
};
