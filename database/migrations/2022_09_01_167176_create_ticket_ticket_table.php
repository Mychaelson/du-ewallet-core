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
        Schema::create('ticket.ticket', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user');
            $table->string('subject');
            $table->integer('category');
            $table->integer('category_sub');
            $table->string('body');
            $table->string('attachment');
            $table->integer('assigned_to');
            $table->string('service');
            $table->string('reff');
            $table->integer('scope');
            $table->integer('priority');
            $table->integer('status');
            $table->integer('rejected_by');
            $table->integer('approved_by');
            $table->date('approved_at');
            $table->integer('tts');
            $table->date('solved');
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
        Schema::dropIfExists('ticket.ticket');
    }
};
