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
        Schema::create('accounts.bank_instruction_lines', function (Blueprint $table) {
            $table->id();
            $table->integer('instruction_id');
            $table->string('title');
            $table->integer('steps');
            $table->string('step_type');
            $table->text('step_value');
            $table->string('lang');
            $table->timestamps();

            $table->index(['instruction_id'], 'bank_instruction_lines_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts.bank_instruction_lines');
    }
};
