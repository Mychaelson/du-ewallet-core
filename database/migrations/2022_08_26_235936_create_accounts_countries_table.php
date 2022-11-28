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
        Schema::create('accounts.countries', function (Blueprint $table) {
            $table->id();
            $table->string('iso', 5);
            $table->string('name', 50);
            $table->string('nicename', 50);
            $table->string('iso3', 5);
            $table->integer('numcode');
            $table->string('phonecode', 5);
            $table->string('flag', 255);
            $table->tinyInteger('default')->default(0);
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
        Schema::dropIfExists('accounts.countries');
    }
};
