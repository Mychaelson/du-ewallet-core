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
        Schema::create('accounts.password_throttles', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->tinyInteger('request_count')->default(0);
            $table->datetime('expires_on');
            $table->tinyInteger('lock')->default(0);
            $table->string('ip', 50);
            $table->timestamps();

            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts.password_throttles');
    }
};
