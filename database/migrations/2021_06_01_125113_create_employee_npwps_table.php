<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeNpwpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_npwps', function (Blueprint $table) {
            $table->id();
            $table->string('number')->nullable();
            $table->foreignId('employee_id');
            $table->foreignId('company_npwp_id')->nullable();
            $table->date('effective_date')->nullable();
            $table->string('type', 10)->nullable();
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
        Schema::dropIfExists('employee_npwps');
    }
}
