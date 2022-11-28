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
        Schema::create('accounts.phone_changes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('phone', 25);
            $table->string('phone_code', 5);
            $table->string('progress', 50);
            $table->string('ip', 50);
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
        Schema::dropIfExists('accounts.phone_changes');
    }
};
