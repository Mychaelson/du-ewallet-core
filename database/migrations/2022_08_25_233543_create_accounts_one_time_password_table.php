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
        Schema::create('accounts.one_time_passwords', function (Blueprint $table) {
            $table->id();
            $table->string('username', 100);
            $table->string('action', 100);
            $table->string('token', 10);
            $table->tinyInteger('tries');
            $table->datetime('expires_at');
            $table->timestamps();

            $table->index(['username', 'action', 'token'], 'otp_main_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts.one_time_passwords');
    }
};
