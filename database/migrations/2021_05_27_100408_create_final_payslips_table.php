<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinalPayslipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_payslips', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->foreignId('employee_id');
            $table->date('start_date_period')->nullable();
            $table->date('end_date_period')->nullable();
            $table->string('type', 50)->nullable();
            $table->text('income')->nullable();
            $table->text('deduction')->nullable();
            $table->string('description', 255)->nullable();
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
        Schema::dropIfExists('final_payslips');
    }
}
