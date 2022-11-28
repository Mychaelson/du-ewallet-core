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
        Schema::create('docs.help', function (Blueprint $table) {
            $table->id();
            $table->integer('user')->nullable(false);
            $table->integer('category')->nullable(false);
            $table->string('locale',10)->charset('utf8')->nullable(false);
            $table->string('group',50)->charset('utf8')->nullable(false);
            $table->string('title',250)->charset('utf8')->nullable(false);
            $table->text('content')->charset('utf8');
            $table->text('keywords');
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
        Schema::dropIfExists('docs.help');
    }
};
