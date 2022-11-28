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
        Schema::create('ppob.transaction_v2', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('product_code');
            $table->integer('label_id');
            $table->string('invoice_no');
            $table->string('product_type')->nullable();
            $table->double('price_sell', 15, 2)->nullable();
            $table->double('admin_fee', 15, 2)->nullable();
            $table->double('discount', 15, 2)->nullable();
            $table->double('total', 15, 2)->nullable(); // price sell + admin fee - discount
            $table->double('price_service', 15, 2)->nullable(); // response vendor
            $table->double('admin_fee_service', 15, 2)->nullable(); //response vendor
            $table->double('profit', 15, 2)->nullable(); // total - (price_service + admin fee service)
            $table->tinyInteger('status');
            $table->integer('service_id');
            $table->json('req_inquiry')->nullable();
            $table->json('res_inquiry')->nullable();
            $table->json('req_payment')->nullable();
            $table->json('res_payment')->nullable();
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
        Schema::dropIfExists('ppob.transaction_v2');
    }
};
