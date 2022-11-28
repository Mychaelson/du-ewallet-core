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
        Schema::create('accounts.company_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id');
            $table->string('account_name');
            $table->string('account_number');
            $table->string('payment_gateway');
            $table->string('payment_method_code');
            $table->integer('is_active');
            $table->integer('is_virtual');
            $table->timestamps();

            $table->index('bank_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts.company_bank_accounts');
    }
};
