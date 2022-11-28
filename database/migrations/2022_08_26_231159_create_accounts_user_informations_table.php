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
        Schema::create('accounts.user_informations', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('mother_name', 100)->nullable();
            $table->string('identity_type', 25)->nullable();
            $table->string('identity_number', 50)->nullable();
            $table->string('identity_image', 255)->nullable();
            $table->date('identity_expired')->nullable();
            $table->string('identity_source', 255)->nullable();
            $table->tinyInteger('identity_status')->default(0);
            $table->string('identity_note', 255)->nullable();
            $table->string('photo', 255)->nullable();
            $table->tinyInteger('photo_status')->default(0);
            $table->string('photo_note', 255)->nullable();
            $table->string('npwp_number', 50)->nullable();
            $table->string('npwp_image', 255)->nullable();
            $table->tinyInteger('npwp_valid')->default(0);
            $table->string('npwp_invalid_reason', 255)->nullable();
            $table->string('kyc_image', 255)->nullable();
            $table->string('passport_number', 50)->nullable();
            $table->string('passport_image', 255)->nullable();
            $table->string('identity_number_of_family', 50)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->tinyInteger('is_valid')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('active')->default(1);
            $table->integer('approved_by')->nullable();
            $table->datetime('requested_at')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('accounts.user_informations');
    }
};
