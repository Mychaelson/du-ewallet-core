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
        Schema::table('ticket.ticket_comment', function (Blueprint $table) {
            $table->integer('user')->nullable()->change();
            $table->text('body')->change();
            $table->text('attachment')->nullable()->change();
            $table->integer('admin')->nullable()->after('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ticket.ticket_comment', function (Blueprint $table) {
            $table->dropColumn('admin');
        });
    }
};
