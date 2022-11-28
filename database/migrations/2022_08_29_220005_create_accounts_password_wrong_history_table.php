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
        Schema::create('accounts.password_wrong_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('ip', 50);
            $table->string('location', 100)->nullable();
            $table->string('device', 100)->nullable();
            $table->string('device_id', 100)->nullable();
            $table->string('message', 255)->nullable();
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
        Schema::dropIfExists('accounts.password_wrong_histories');
    }
};
