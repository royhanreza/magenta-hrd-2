<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameLeaveTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->renameColumn('leave_total', 'total_leave');
            $table->renameColumn('leave_taken', 'taken_leave');
            $table->renameColumn('carry_forward_total', 'total_carry_forward');
            $table->renameColumn('carry_forward_taken', 'taken_carry_forward');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->renameColumn('total_leave', 'leave_total');
            $table->renameColumn('taken_leave', 'leave_taken');
            $table->renameColumn('total_carry_forward', 'carry_forward_total');
            $table->renameColumn('taken_carry_forward', 'carry_forward_taken');
        });
    }
}
