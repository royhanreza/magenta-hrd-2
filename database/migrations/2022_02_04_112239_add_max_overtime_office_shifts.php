<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxOvertimeOfficeShifts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_shifts', function (Blueprint $table) {
            $table->tinyInteger('monday_max_overtime')->nullable()->default(0)->after('monday_work_as_overtime');
            $table->tinyInteger('tuesday_max_overtime')->nullable()->default(0)->after('tuesday_work_as_overtime');
            $table->tinyInteger('wednesday_max_overtime')->nullable()->default(0)->after('wednesday_work_as_overtime');
            $table->tinyInteger('thursday_max_overtime')->nullable()->default(0)->after('thursday_work_as_overtime');
            $table->tinyInteger('friday_max_overtime')->nullable()->default(0)->after('friday_work_as_overtime');
            $table->tinyInteger('saturday_max_overtime')->nullable()->default(0)->after('saturday_work_as_overtime');
            $table->tinyInteger('sunday_max_overtime')->nullable()->default(0)->after('sunday_work_as_overtime');
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
            $table->dropColumn(['monday_max_overtime', 'tuesday_max_overtime', 'wednesday_max_overtime', 'thursday_max_overtime', 'friday_max_overtime', 'saturday_max_overtime', 'sunday_max_overtime']);
        });
    }
}
