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
        Schema::create('accounts.feed_source', function (Blueprint $table) {
            $table->increments('id');
            $table->string('feed_source');
            $table->text('source_url');
            $table->text('latest_data');
            $table->date('latest_fetch');
            $table->text('api_detail');
            $table->tinyInteger('status');
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
        Schema::dropIfExists('accounts.feed_source');
    }
};
