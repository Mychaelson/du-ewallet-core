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
        Schema::create('notif.notif_category', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('icon');
            $table->string('last_activity');
            $table->string('activity');
            $table->string('namespace');
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
        Schema::dropIfExists('notif.notif_category');
    }
};
