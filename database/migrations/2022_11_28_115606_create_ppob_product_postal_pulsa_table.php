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
        Schema::create('ppob.product_portalpulsa', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->string('provider_sub');
            $table->string('operator');
            $table->string('operator_sub');
            $table->string('code');
            $table->string('description');
            $table->double('price', 15, 2);
            $table->string('status');
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
        Schema::dropIfExists('ppob.product_portalpulsa');
    }
};
