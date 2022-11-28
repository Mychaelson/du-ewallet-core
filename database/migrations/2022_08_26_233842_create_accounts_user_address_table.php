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
        Schema::create('accounts.user_address', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name', 100)->nullable();
            $table->string('phone', 25)->nullable();
            $table->tinyInteger('is_main')->default(0);
            $table->string('address', 255)->nullable();
            $table->integer('province_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('subdistrict_id')->nullable();
            $table->bigInteger('village_id')->nullable();
            $table->string('postal_code', 25)->nullable();
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
        Schema::dropIfExists('accounts.user_address');
    }
};
