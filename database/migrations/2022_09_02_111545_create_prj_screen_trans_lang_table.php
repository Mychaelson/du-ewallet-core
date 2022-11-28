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
        Schema::create('lang.prj_screen_trans_lang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('screen_trans_id')->nullable();
            $table->unsignedBigInteger('trans_lang_id')->nullable();
            $table->string('translation');
            $table->unsignedTinyInteger('status')->default(1);
            $table->dateTime('last_status_date')->nullable()->default(NULL);
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
        Schema::dropIfExists('lang.prj_screen_trans_lang');
    }
};
