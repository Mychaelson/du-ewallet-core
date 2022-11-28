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
        Schema::create('ppob.digital_products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 256);
            $table->string('name', 256)->nullable();
            $table->string('slug', 256);
            $table->string('image', 256);
            $table->string('description', 256);
            $table->string('danom', 256);
            $table->string('provider', 256);

            $table->integer('order')->nullable();
            $table->string('currency', 3)->nullable();
            $table->double('price', 11, 2)->nullable();
            $table->double('price_agent', 11, 2)->nullable();
            $table->double('reseller_price', 11, 2)->nullable();
            $table->integer('profit_fee')->nullable();

            $table->double('admin_fee', 11, 2)->nullable();
            $table->double('base_price', 11, 2)->nullable();
            $table->integer('ppn')->nullable();
            $table->integer('pph')->nullable();
            $table->integer('status')->nullable();

            $table->integer('category_id')->nullable();
            $table->integer('parent_id')->nullable();

            $table->text('meta');
            $table->string('icon', 256);
            $table->integer('profit_percentage')->nullable();
            $table->integer('digital_product_service_id')->nullable();
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
        Schema::dropIfExists('digital_products');
    }
};
