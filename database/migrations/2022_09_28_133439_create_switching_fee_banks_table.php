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
        Schema::create('wallet.switching_fee_banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_from');
            $table->string('bank_to');
            $table->decimal('fee', 13, 2);
            $table->decimal('cgs', 13, 2);
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
        Schema::dropIfExists('wallet.switching_fee_banks');
    }
};
