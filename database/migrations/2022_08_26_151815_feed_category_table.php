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
        Schema::create('accounts.feed_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_name',255);
            $table->integer('category_type');
            $table->integer('category_id');
            $table->text('category_data');
            $table->date('latest_feed');
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
        Schema::dropIfExists('accounts.feed_category');
    }
};
