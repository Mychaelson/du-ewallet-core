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
        Schema::create('wallet.wallet_labels', function (Blueprint $table) {
            $table->id();
            $table->Integer('user')->nullable();
            $table->string('name');
            $table->string('icon', 500)->nullable();
            $table->string('background', 500)->nullable();
            $table->string('color');
            $table->Integer('spending')->nullable();
            $table->Integer('default')->nullable();
            $table->Integer('organization')->nullable();
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
        Schema::dropIfExists('wallet.wallet_labels');
    }
};
