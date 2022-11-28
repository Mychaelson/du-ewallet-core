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
        Schema::create('payment.card', function (Blueprint $table) {
            $table->id();
            $table->integer('user');
            $table->string('bank', 256);
            $table->bigInteger('number');
            $table->integer('exp_year');
            $table->integer('exp_month');
            $table->integer('status');
            $table->integer('visibility');
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
        Schema::dropIfExists('payment.card');
    }
};
