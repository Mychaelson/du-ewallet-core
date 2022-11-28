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
        Schema::create('promotions.agreement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('merchant_id')->unique();
            $table->string('transaction_id')->nullable();
            $table->string('document_number');
            $table->string('document_name');
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->float('fund', 15, 2);
            $table->integer('approved')->default(0);
            $table->string('approved_by')->nullable()->default(NULL);
            $table->float('available_fund', 15, 2);
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
        Schema::dropIfExists('promotions.agreement');
    }
};
