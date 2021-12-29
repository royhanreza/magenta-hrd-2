<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkingHoursOfficeShifts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_shifts', function (Blueprint $table) {
            $table->after('monday_out_time', function ($table) {
                $table->tinyInteger('monday_working_hours')->nullable()->default(0);
                $table->tinyInteger('monday_working_hours_editable')->nullable()->default(0);
            });
            $table->after('tuesday_out_time', function ($table) {
                $table->tinyInteger('tuesday_working_hours')->nullable()->default(0);
                $table->tinyInteger('tuesday_working_hours_editable')->nullable()->default(0);
            });
            $table->after('wednesday_out_time', function ($table) {
                $table->tinyInteger('wednesday_working_hours')->nullable()->default(0);
                $table->tinyInteger('wednesday_working_hours_editable')->nullable()->default(0);
            });
            $table->after('thursday_out_time', function ($table) {
                $table->tinyInteger('thursday_working_hours')->nullable()->default(0);
                $table->tinyInteger('thursday_working_hours_editable')->nullable()->default(0);
            });
            $table->after('friday_out_time', function ($table) {
                $table->tinyInteger('friday_working_hours')->nullable()->default(0);
                $table->tinyInteger('friday_working_hours_editable')->nullable()->default(0);
            });
            $table->after('saturday_out_time', function ($table) {
                $table->tinyInteger('saturday_working_hours')->nullable()->default(0);
                $table->tinyInteger('saturday_working_hours_editable')->nullable()->default(0);
            });
            $table->after('sunday_out_time', function ($table) {
                $table->tinyInteger('sunday_working_hours')->nullable()->default(0);
                $table->tinyInteger('sunday_working_hours_editable')->nullable()->default(0);
            });
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
            $table->dropColumn([
                'monday_working_hours',
                'monday_working_hours_editable',
                'tuesday_working_hours',
                'tuesday_working_hours_editable',
                'wednesday_working_hours',
                'wednesday_working_hours_editable',
                'thursday_working_hours',
                'thursday_working_hours_editable',
                'friday_working_hours',
                'friday_working_hours',
                'saturday_working_hours_editable',
                'saturday_working_hours_editable',
                'sunday_working_hours_editable',
                'sunday_working_hours_editable',
            ]);
        });
    }
}
