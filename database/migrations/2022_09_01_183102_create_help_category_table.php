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
        Schema::create('docs.help_category', function (Blueprint $table) {
            $table->id();
            $table->integer('user')->nullable(false);
            $table->string('name',50)->nullable(false);
            $table->string('group',50)->nullable(false);
            $table->string('slug',100)->nullable(false);
            $table->string('locale',10)->nullable(false);
            $table->string('icon',250)->nullable(false);
            $table->timestamps();

            $table->index(['slug', 'locale', 'group'], 'by_slug_locale_group');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs.help_category');
    }
};
