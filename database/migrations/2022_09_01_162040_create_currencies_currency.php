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
        Schema::create('currencies.currency', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 64);
            $table->string('name', 100);
            $table->string('symbol', 5);
            $table->double('price');
            $table->double('adj_sell');
            $table->double('adj_buy');
            $table->double('best_price');
            $table->double('lowest_price');
            $table->double('limit_up');
            $table->double('limit_down');
            $table->integer('status');
            $table->date('created_at');
            $table->date('updated_at');

            $table->index('code');
            $table->index('name');
            $table->index('symbol');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies.currency');
    }
};
