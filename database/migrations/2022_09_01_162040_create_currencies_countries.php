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
        Schema::create('currencies.countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iso', 2);
            $table->string('name', 100);
            $table->string('nicename', 150);
            $table->string('iso3', 3);
            $table->integer('numcode');
            $table->integer('phonecode');
            $table->string('flag', 150);
            $table->string('curr_index', 150);
            $table->date('created_at');
            $table->date('updated_at');
            $table->integer('default');

            $table->index('iso');
            $table->index('iso3');
            $table->index('curr_index');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies.countries');
    }
};
