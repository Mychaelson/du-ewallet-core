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
        Schema::table('ticket.ticket', function (Blueprint $table) {
            $table->text('body')->change();
            $table->text('attachment')->nullable()->change();
            $table->integer('assigned_to')->nullable()->change();
            $table->string('service')->nullable()->change();
            $table->string('reff')->nullable()->change();
            $table->integer('rejected_by')->nullable()->change();
            $table->integer('approved_by')->nullable()->change();
            $table->date('approved_at')->nullable()->change();
            $table->date('solved')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
