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
        Schema::create('wallet.wallet_topup', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('wallet_id');
            $table->integer('label_id')->nullable();
            $table->double('amount', 13, 2)->nullable();
            $table->double('fee', 13, 2)->nullable();
            $table->double('total', 13, 2)->nullable();
            $table->string('reff')->nullable();
            $table->string('confirmation')->nullable();
            $table->integer('bank_to_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('payment_method_code')->nullable();
            $table->tinyInteger('status')->default(2);
            $table->dateTime('expires')->nullable();
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
        Schema::dropIfExists('wallet.wallet_topup');
    }
};
