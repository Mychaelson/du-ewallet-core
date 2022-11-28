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
        Schema::create('connect.apps_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id')->index();
            $table->unsignedBigInteger('cart_id')->nullable()->index();
            $table->string('invoice_no', 100)->nullable()->index();
            $table->unsignedBigInteger('bill_id')->nullable()->index();
            $table->string('pay_method', 50)->nullable()->index();
            $table->double('amount')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->dateTime('expired_at')->nullable();
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
        Schema::dropIfExists('connect.apps_invoices');
    }
};
