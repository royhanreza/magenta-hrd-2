<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_submissions', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_filing');
            $table->foreignId('employee_id');
            $table->text('leave_dates');
            // $table->string('attachment', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('status', 30)->nullable()->default('pending');
            $table->string('created_by', 255)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('leave_submissions');
    }
}
