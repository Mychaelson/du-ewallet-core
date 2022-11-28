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
        Schema::create('lang.prj_screen_trans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_screen_id')->nullable();
            $table->string('key')->index('prj_scr_trn_key');
            $table->string('default_translation');
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
        Schema::dropIfExists('lang.prj_screen_trans');
    }
};
