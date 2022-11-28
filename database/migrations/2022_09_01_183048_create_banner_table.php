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
        Schema::create('banner.banners', function (Blueprint $table) {
            $table->id();
            $table->string('image',250);
            $table->string('cover',250);
            $table->string('title',250);
            $table->string('highlight',250);
            $table->text('terms');
            $table->string('activity',100);
            $table->string('label',100);
            $table->string('web',250);
            $table->string('phone',15);
            $table->string('email',250);
            $table->datetime('time_start')->nullable();
            $table->datetime('time_end')->nullable();
            $table->string('group');
            $table->text('params');
            $table->tinyInteger('status')->default(2); // 0 deleted ,1 hide ,2 active
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
        Schema::dropIfExists('banner.banners');
    }
};
