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
        Schema::create('accounts.bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('bank_id');
            $table->string('account_name', 100);
            $table->string('account_number', 100);
            $table->tinyInteger('is_main')->default(0);
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('is_verify')->default(0);
            $table->tinyInteger('is_virtual')->default(0);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts.bank_accounts');
    }
};
