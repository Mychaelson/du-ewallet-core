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
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->string('ip', 50)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('device', 100)->nullable();
            $table->string('device_id', 100)->nullable();
            $table->string('imei', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oauth_access_tokens', function (Blueprint $table) {
            $table->dropColumn('ip');
            $table->dropColumn('location');
            $table->dropColumn('device');
            $table->dropColumn('device_id');
            $table->dropColumn('imei');
        });
    }
};
