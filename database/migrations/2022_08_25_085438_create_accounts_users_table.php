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
        Schema::create('accounts.users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('name', 100)->nullable();
            $table->string('nickname', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->tinyInteger('email_verified')->default(0);
            $table->string('email_hash', 250)->nullable();
            $table->string('phone', 25);
            $table->string('phone_code', 5);
            $table->string('password', 100);
            $table->string('password_extra', 100)->nullable();
            $table->tinyInteger('is_active_password')->default(0);
            $table->tinyInteger('check_extra_password')->default(0);
            $table->string('avatar', 250)->nullable();
            $table->string('place_of_birth', 100)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->tinyInteger('gender')->default(0);
            $table->string('blood_type', 2)->nullable();
            $table->tinyInteger('marital_status')->default(0);
            $table->string('religion', 50)->nullable();

            $table->integer('group_id')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('watch_status')->default(0);
            $table->tinyInteger('user_type')->default(0);
            $table->tinyInteger('verified')->default(0);
            $table->string('locale', 10)->default('id');
            $table->string('timezone', 100)->default('Asia/Jakarta');
            $table->string('location', 250)->nullable();

            $table->string('referral_by', 100)->nullable();
            $table->string('referral_code', 100)->nullable();
            $table->tinyInteger('referral_change_count')->default(0);

            $table->string('telegram_id', 100)->nullable();
            $table->string('whatsapp', 100)->nullable();
            $table->tinyInteger('whatsapp_active')->default(0);
            $table->string('gcm_token', 250)->nullable();
            $table->string('device_token', 250)->nullable();
            $table->string('onesignal_id', 250)->nullable();
            $table->string('remember_token', 250)->nullable();
            $table->string('merchant_token', 250)->nullable();
            $table->string('nfc_device', 250)->nullable();
            $table->string('nfc_identify', 250)->nullable();
            $table->string('main_device', 250)->nullable();
            $table->string('main_device_name', 250)->nullable();

            $table->datetime('date_suspended')->nullable();
            $table->string('suspended_reason', 250)->nullable();
            $table->datetime('last_login')->nullable();
            $table->datetime('date_activated')->nullable();
            $table->timestamps();

            $table->index('username', 'users_username_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts.users');
    }
};
