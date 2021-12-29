<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsOfficeShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_shifts', function (Blueprint $table) {
            $table->string('monday_status', 20)->nullable();
            $table->string('tuesday_status', 20)->nullable();
            $table->string('wednesday_status', 20)->nullable();
            $table->string('thursday_status', 20)->nullable();
            $table->string('friday_status', 20)->nullable();
            $table->string('saturday_status', 20)->nullable();
            $table->string('sunday_status', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('office_shifts', function (Blueprint $table) {
            $table->dropColumn(['monday_status', 'tuesday_status', 'wednesday_status', 'thursday_status', 'friday_status', 'saturday_status', 'sunday_status']);
        });
    }
}
