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
        Schema::create('wallet.wallet_transfers', function (Blueprint $table) {
            $table->id();
            $table->text('message')->nullable();
            $table->text('message_reply')->nullable();
            $table->string('background', 500)->nullable();
            $table->Integer('from'); //wallet_id_from
            $table->Integer('to'); //wallet_id_to
            $table->Integer('label');
            $table->decimal('amount', 13, 2);
            $table->string('reff', 500)->nullable();
            $table->text('description_from')->nullable();
            $table->text('description_to')->nullable();
            $table->dateTime('schedule')->nullable();
            $table->unsignedInteger('repeat')->nullable();
            $table->timestamps();

            $table->index(['from', 'to', 'label'], 'wallet_transfers_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet.wallet_transfers');
    }
};
