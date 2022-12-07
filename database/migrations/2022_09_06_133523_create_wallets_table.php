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
        Schema::create('wallet.wallets', function (Blueprint $table) {
            $table->id();
            $table->string('locker');
            $table->integer('user_id');
            $table->string('currency', 100);
            $table->double('balance', 13, 2)->default(0);
            $table->double('ncash', 13, 2)->default(0);
            $table->double('hold', 13, 2)->default(0);
            $table->double('reversal', 13, 2)->default(0);
            $table->unsignedTinyInteger('type')->default(1);
            $table->integer('merchant')->default(0);
            $table->unsignedTinyInteger('lock_in')->default(0);
            $table->unsignedTinyInteger('lock_out')->default(0);
            $table->unsignedTinyInteger('lock_wd')->default(0);
            $table->unsignedTinyInteger('lock_tf')->default(0);
            $table->unsignedTinyInteger('lock_nv_rdm')->default(0);
            $table->unsignedTinyInteger('lock_pm')->default(0);
            $table->unsignedTinyInteger('lock_nv_crt')->default(0);
            $table->timestamps();

            $table->index(['user_id'], 'wallets_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet.wallets');
    }
};
