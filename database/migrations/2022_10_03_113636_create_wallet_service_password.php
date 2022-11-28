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
        Schema::create('wallet.service_password', function (Blueprint $table) {
            $table->id();
            $table->Integer('user');
            $table->string('password');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::table('ppob.payment_schedules', function($table)
        {
            $table->dateTime('last_payment')->nullable()->change();
            $table->dateTime('last_inquiry')->nullable()->change();
            $table->integer('transaction_id')->nullable()->change();
            $table->string('note', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet.service_password');
    }
};
