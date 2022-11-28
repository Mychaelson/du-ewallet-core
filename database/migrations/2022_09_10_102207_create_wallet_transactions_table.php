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
        Schema::create('wallet.wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('wallet_id')->nullable();
            $table->string('reff_id');
            $table->decimal('amount', 13, 2);
            $table->string('transaction_type');
            $table->string('status')->nullable();
            $table->string('note')->nullable();
            $table->integer('label_id')->nullable();
            $table->double('balance_before', 13, 2)->nullable();
            $table->string('location')->nullable();
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
        Schema::dropIfExists('wallet.wallet_transactions');
    }
};
