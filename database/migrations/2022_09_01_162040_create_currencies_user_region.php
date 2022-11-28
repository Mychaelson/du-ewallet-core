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
        Schema::create('currencies.user_region', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('region_type');
            $table->integer('region_id');
            $table->date('created_at');
            $table->date('updated_at');

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
        Schema::dropIfExists('currencies.user_region');
    }
};
