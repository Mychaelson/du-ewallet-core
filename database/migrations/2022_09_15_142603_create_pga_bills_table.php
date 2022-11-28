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
        Schema::create('payment.pga_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id');
            $table->string('action');
            $table->string('merchant_code')->nullable();
            $table->string('reference_no', 25)->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('description', 500)->nullable();
            $table->decimal('net_amount',13,2)->nullable();
            $table->decimal('surcharge',13,2)->nullable();
            $table->string('surcharge_to')->nullable();
            $table->decimal('amount',13,2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_code')->nullable();
            $table->string('pay_code')->nullable();
            $table->string('pay_qrcode')->nullable();
            $table->string('pay_checkout_url', 500)->nullable();
            $table->string('pay_mobile_deeplink')->nullable();
            $table->integer('paid_status')->nullable();
            $table->string('paid_description')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->string('form_url', 500)->nullable();
            $table->string('status')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment.pga_bills');
    }
};
