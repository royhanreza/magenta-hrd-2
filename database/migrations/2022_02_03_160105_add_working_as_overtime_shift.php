<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkingAsOvertimeShift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_shifts', function (Blueprint $table) {
            $table->tinyInteger('monday_work_as_overtime')->nullable()->default(0)->after('monday_lateness');
            $table->tinyInteger('tuesday_work_as_overtime')->nullable()->default(0)->after('tuesday_lateness');
            $table->tinyInteger('wednesday_work_as_overtime')->nullable()->default(0)->after('wednesday_lateness');
            $table->tinyInteger('thursday_work_as_overtime')->nullable()->default(0)->after('thursday_lateness');
            $table->tinyInteger('friday_work_as_overtime')->nullable()->default(0)->after('friday_lateness');
            $table->tinyInteger('saturday_work_as_overtime')->nullable()->default(0)->after('saturday_lateness');
            $table->tinyInteger('sunday_work_as_overtime')->nullable()->default(0)->after('sunday_lateness');
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
            $table->dropColumn(['monday_work_as_overtime', 'tuesday_work_as_overtime', 'wednesday_work_as_overtime', 'thursday_work_as_overtime', 'friday_work_as_overtime', 'saturday_work_as_overtime', 'sunday_work_as_overtime']);
        });
    }
}
