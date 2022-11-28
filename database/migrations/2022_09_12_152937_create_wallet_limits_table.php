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
        Schema::create('wallet.wallet_limits', function (Blueprint $table) {
            $table->id();
            $table->integer('wallet');
            $table->decimal('withdraw_daily', 13, 2)->nullable();
            $table->decimal('transfer_daily', 13, 2)->nullable();
            $table->decimal('payment_daily', 13, 2)->nullable();
            $table->decimal('topup_daily', 13, 2)->nullable();
            $table->decimal('switching_max', 13, 2)->nullable();
            $table->decimal('max_balance', 13, 2)->nullable();
            $table->decimal('transaction_monthly', 13, 2)->nullable();
            $table->integer('free_withdraw')->nullable();
            $table->integer('max_group_transfer')->nullable();
            $table->integer('max_group_withdraw')->nullable();
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
        Schema::dropIfExists('wallet.wallet_limits');
    }
};
