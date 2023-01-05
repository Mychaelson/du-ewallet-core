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
        Schema::create('notif.notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->integer('notifiable_id');
            $table->string('notifiable_type', 50);
            $table->string('data', 4096);
            $table->string('read_at', 50)->nullable();
            $table->string('category', 50);
            $table->string('icon', 128);
            $table->integer('merchant_id');
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
        Schema::dropIfExists('notif.notifications');
    }
};
