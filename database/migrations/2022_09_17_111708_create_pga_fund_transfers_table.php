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
        Schema::create('payment.pga_fund_transfers', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id');
            $table->string('action');
            $table->string('reference_no')->nullable();
            $table->string('unique_id');
            $table->decimal('amount', 13,2)->nullable();
            $table->decimal('fee', 13,2)->nullable();
            $table->decimal('merchant_surcharge_rate', 13,2)->nullable();
            $table->string('charge_to')->nullable();
            $table->string('payout_amount')->nullable();
            $table->integer('disbursement_status')->nullable();
            $table->string('disbursement_description')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('status');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['transaction_id', 'action', 'bank_code', 'bank_account_number'], 'pga_fund_transfers_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment.pga_fund_transfers');
    }
};
