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
        Schema::create('lang.project', function (Blueprint $table) {
            $table->id();
            $table->string('project_uid')->index('prj_prj_uid');
            $table->string('project_description')->nullable()->default(null);
            $table->string('project_image')->default(null)->nullable();
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
        Schema::dropIfExists('lang.project');
    }
};
