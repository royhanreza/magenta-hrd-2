<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatenessOfficeShift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('office_shifts', function (Blueprint $table) {
            $table->tinyInteger('monday_lateness')->nullable()->default(1)->after('monday_out_time');
            $table->tinyInteger('tuesday_lateness')->nullable()->default(1)->after('tuesday_out_time');
            $table->tinyInteger('wednesday_lateness')->nullable()->default(1)->after('wednesday_out_time');
            $table->tinyInteger('thursday_lateness')->nullable()->default(1)->after('thursday_out_time');
            $table->tinyInteger('friday_lateness')->nullable()->default(1)->after('friday_out_time');
            $table->tinyInteger('saturday_lateness')->nullable()->default(1)->after('saturday_out_time');
            $table->tinyInteger('sunday_lateness')->nullable()->default(1)->after('sunday_out_time');
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
            $table->dropColumn(['monday_lateness', 'tuesday_lateness', 'wednesday_lateness', 'thursday_lateness', 'friday_lateness', 'saturday_lateness', 'sunday_lateness']);
        });
    }
}
