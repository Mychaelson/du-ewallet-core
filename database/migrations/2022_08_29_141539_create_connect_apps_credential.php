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
        Schema::create('connect.apps_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('merchant_id')->index();
            $table->string('spass_id', 20)->nullable()->index();
            $table->string('spass_value', 100)->nullable()->index();
            $table->string('client_secret', 100)->nullable()->index();
            $table->text('client_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->tinyInteger('revoked')->default(0);
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
        Schema::dropIfExists('connect.apps_credentials');
    }
};
