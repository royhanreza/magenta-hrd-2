<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimeSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtime_submissions', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_filing');
            $table->foreignId('employee_id');
            $table->date('date');
            $table->time('overtime_start');
            $table->time('overtime_end');
            $table->string('work', 255);
            $table->string('note', 255)->nullable();
            $table->string('status', 20)->nullable()->default('pending');
            $table->foreignId('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('overtime_submissions');
    }
}
