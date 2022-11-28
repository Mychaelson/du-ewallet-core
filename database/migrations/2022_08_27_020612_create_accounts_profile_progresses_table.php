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
        Schema::create('accounts.profile_progresses', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->tinyInteger('basic')->default(0);
            $table->tinyInteger('profile')->default(0);
            $table->tinyInteger('contact')->default(0);
            $table->tinyInteger('document')->default(0);
            $table->tinyInteger('address')->default(0);
            $table->tinyInteger('tax_information')->default(0);
            $table->tinyInteger('recovery_security')->default(0);
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
        Schema::dropIfExists('accounts.profile_progresses');
    }
};
