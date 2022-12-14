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
        Schema::create('ppob.biller_services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('desctription')->nullable();
            $table->unsignedInteger('biller_id');
            $table->string('service');
            $table->tinyInteger('status')->default(0);
            $table->timestamps();

            $table->index(['biller_id'], 'biller_services_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ppob.biller_services');
    }
};
