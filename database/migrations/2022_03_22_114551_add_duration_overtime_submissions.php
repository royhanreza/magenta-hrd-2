<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationOvertimeSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('overtime_submissions', function (Blueprint $table) {
            $table->tinyInteger('duration')->nullable()->default(0)->after('overtime_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('overtime_submissions', function (Blueprint $table) {
            $table->dropColumn(['duration']);
        });
    }
}
