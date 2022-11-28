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
        Schema::create('ppob.digital_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->text('description');
            $table->integer('order')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('type', 255);
            $table->text('meta');
            $table->string('group', 255);

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
        Schema::dropIfExists('ppob.digital_categories');
    }
};
