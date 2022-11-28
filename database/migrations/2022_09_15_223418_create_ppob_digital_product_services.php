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
        Schema::create('ppob.digital_product_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('service_id');
            $table->double('base_price', 11, 2)->default(0);
            $table->double('admin_fee', 11, 2)->default(0);
            $table->string('code', 50)->nullable();
            $table->json('meta')->nullable();
            $table->tinyInteger('status')->default(0); // 0 => inactive, 1 => active (boolean)
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
        Schema::dropIfExists('ppob.digital_product_services');
    }
};
