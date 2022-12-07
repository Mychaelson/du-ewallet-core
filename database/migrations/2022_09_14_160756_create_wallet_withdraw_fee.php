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
        Schema::create('wallet.wallet_withdraw_fee', function (Blueprint $table) {
            $table->id();
            $table->integer('wallet_id');
            $table->integer('bank_id');
            $table->decimal('fee', 13, 2)->default(0.00);
            $table->tinyInteger('status');
            $table->timestamps();

            $table->index(['wallet_id', 'bank_id'], 'wallet_withdraw_fee_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet.wallet_withdraw_fee');
    }
};
