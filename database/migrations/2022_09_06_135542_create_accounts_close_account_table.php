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
        Schema::create('accounts.close_account', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->tinyInteger('status')->default(1); // 1. requested, 2. pending, 3. on prosess,  4. rejected, 5. done
            $table->string('emoticon', 255)->nullable();
            $table->string('content', 255)->nullable();
            $table->string('approval_by', 255)->nullable();
            $table->datetime('approved_at')->nullable();
            $table->string('reason', 255)->nullable();
            $table->text('meta')->nullable();
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
        Schema::dropIfExists('accounts.close_account');
    }
};
