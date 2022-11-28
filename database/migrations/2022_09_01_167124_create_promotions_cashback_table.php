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
        Schema::create('promotions.cashback', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('promotion_id')->nullable()->default(NULL);
            $table->float('percentage', 5, 2);
            $table->float('amount', 15, 2);
            $table->float('transaction_amount', 15, 2);
            $table->string('transaction_ref')->nullable()->default(NULL);
            $table->integer('coupon')->nullable()->default(NULL);
            $table->string('transaction_id');
            $table->integer('redeemed_by');
            $table->dateTimeTz('redeemed_at');
            $table->dateTimeTz('cashout_at')->nullable()->default(NULL);
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->index('status');
            $table->index('transaction_id');
            $table->index('redeemed_by');
            $table->index('cashout_at');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions.cashback');
    }
};
