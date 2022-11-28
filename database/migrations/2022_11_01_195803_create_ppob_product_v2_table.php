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
        Schema::create('ppob.product_v2', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('provider')->nullable();
            $table->integer('category_id');
            $table->decimal('denom', 15, 2)->default(0);
            $table->decimal('price_sell', 15, 2)->default(0);
            $table->decimal('price_buy', 15, 2)->default(0);
            $table->decimal('admin_fee', 15, 2)->default(0); // internal
            $table->decimal('discount', 15, 2)->default(0);
            $table->string('product_type')->nullable(); // postpaid  / prepaid
            $table->tinyInteger('status');
            $table->integer('service_id');
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
        Schema::dropIfExists('ppob.product_v2');
    }
};
