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
        Schema::create('wallet.wallet_withdraw', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('wallet_id');
            $table->tinyInteger('label');
            $table->decimal('amount', 13, 2)->default(0.00);
            $table->string('location')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->decimal('pg_fee', 13, 2)->default(0.00);
            $table->dateTime('pg_informed')->nullable();
            $table->dateTime('pg_confirmed')->nullable();
            $table->string('agent')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->json('notify')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'wallet_id'], 'wallet_withdraw_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet.wallet_withdraw');
    }
};
