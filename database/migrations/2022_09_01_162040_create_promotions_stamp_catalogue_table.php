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
        Schema::create('promotions.stamp_catalogue', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('merchant');
            $table->string('name');
            $table->string('slug');
            $table->string('category');
            $table->text('description');
            $table->text('images');
            $table->integer('stamp_required');
            $table->integer('quantity');
            $table->integer('quantity_exchanged');
            $table->date('start_at')->nullable()->default(NULL);
            $table->date('end_at')->nullable()->default(NULL);
            $table->date('terminated_at')->nullable()->default(NULL);
            $table->string('status')->default('DRAFT');
            $table->text('properties');
            $table->tinyInteger('claim_p_day');
            $table->tinyInteger('claim_p_day_user');
            $table->unsignedInteger('created_by')->nullable()->default(NULL);
            $table->unsignedInteger('user_id')->nullable()->default(NULL);
            $table->timestamps();

            $table->index('merchant');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotions.stamp_catalogue');
    }
};
