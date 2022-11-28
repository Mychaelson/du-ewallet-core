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
        Schema::create('lang.project_screen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_version_id')->nullable();
            $table->string('screen_name')->index('prj_scr_scrname');
            $table->string('screen_description')->nullable();
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
        Schema::dropIfExists('lang.project_screen');
    }
};
