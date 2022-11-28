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
        Schema::create('docs.document', function (Blueprint $table) {
            $table->id();
            $table->integer('user')->nullable(false);
            $table->string('title',100)->nullable(false);
            $table->string('slug',100)->nullable(false);
            $table->string('locale',10)->nullable(false);
            $table->text('content');
            $table->string('version',10)->nullable(false);
            $table->timestamps();

            $table->index(['slug', 'locale'], 'by_slug_locale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('docs.document');
    }
};
