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
        Schema::create('accounts.media', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('group_id')->nullable();
            $table->string('name');
            $table->string('filename');
            $table->string('extension', 5);
            $table->string('mimetype', 100);
            $table->integer('filesize');
            $table->string('filepath');
            $table->string('url');
            $table->string('thumb')->nullable();
            $table->string('type', 20);
            $table->string('disk')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('publish')->default(0);
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
        Schema::dropIfExists('accounts.media');
    }
};
