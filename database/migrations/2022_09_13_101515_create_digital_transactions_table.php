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
        Schema::create('ppob.digital_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 50)->nullable();
            $table->string('code', 40);
            $table->string('order_id', 22)->nullable();
            $table->string('currency', 3)->nullable();
            $table->integer('user_id');
            $table->string('phone')->nullable();
            $table->string('customer_id', 20);
            $table->double('price', 15, 2)->nullable();
            $table->double('admin_fee', 15, 2)->nullable();
            $table->double('amount', 15, 2)->nullable();
            $table->double('discount_amount', 15, 2)->nullable();
            $table->double('voucher_amount', 15, 2)->nullable();
            $table->double('ncash', 15, 2)->nullable();
            $table->double('total', 15, 2)->nullable();
            $table->double('base_price', 15, 2)->nullable();
            $table->string('status');
            $table->integer('biller_id');
            $table->string('service', 500);
            $table->json('product_snap')->nullable();
            $table->json('request_data')->nullable();
            $table->json('inquiry_data')->nullable();
            $table->json('result')->nullable();
            $table->json('response_data')->nullable();
            $table->json('meta')->nullable();
            $table->integer('type')->nullable();
            $table->string('category', 255);
            $table->string('payment_channel', 255)->nullable();
            $table->json('payment_information');
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
        Schema::dropIfExists('ppob.digital_transactions');
    }
};
