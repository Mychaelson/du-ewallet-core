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
        Schema::create('connect.apps_buttons', function (Blueprint $table) {
            $table->id();
            $table->string('btn_name', 100)->nullable();
            $table->text('btn_image')->nullable();
            $table->text('btn_html')->nullable();
            $table->text('btn_sdk');
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
        Schema::dropIfExists('connect.apps_buttons');
    }
};
