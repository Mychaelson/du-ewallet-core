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
        Schema::create('ppob.payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('customer_id', 255)->nullable();
            $table->integer('product_id');
            $table->string('code', 255)->nullable();
            $table->string('category', 255);
            $table->dateTime('payment_at')->nullable();
            $table->integer('repeat')->nullable();
            $table->dateTime('last_payment');
            $table->double('price', 15, 2)->nullable();
            $table->integer('on_schedule')->nullable();
            $table->dateTime('last_inquiry');
            $table->integer('transaction_id');
            $table->integer('status')->nullable();
            $table->string('wallet_hash', 255)->nullable();
            $table->integer('wallet_id');
            $table->string('note', 255);
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
        Schema::dropIfExists('ppob.payment_schedules');
    }
};
