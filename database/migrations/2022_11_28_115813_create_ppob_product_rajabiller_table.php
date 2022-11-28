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
        Schema::create('ppob.product_rajabillers', function (Blueprint $table) {
            $table->id();
            $table->string('idproduk');
            $table->string('namaproduk');
            $table->string('groupproduk');
            $table->double('harga_jual', 15, 2);
            $table->double('biaya_admin', 15, 2);
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
        Schema::dropIfExists('ppob_product_rajabillers');
    }
};
