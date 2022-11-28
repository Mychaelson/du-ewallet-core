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
        Schema::create('payment.bill_payment', function (Blueprint $table) {
            $table->id();
            $table->integer('bill');
            $table->integer('status');
            $table->string('method', 50);
            $table->integer('internal');
            $table->string('data', 1050);
            $table->string('amount', 50);
            $table->string('object', 512);
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
        Schema::dropIfExists('payment.bill_payment');
    }
};
