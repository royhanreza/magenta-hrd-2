<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeBpjsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_bpjs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->string('bpjs_ketenagakerjaan_number', 255)->nullable();
            $table->date('bpjs_ketenagakerjaan_effective_date', 255)->nullable();
            $table->string('bpjs_kesehatan_number', 255)->nullable();
            $table->date('bpjs_kesehatan_effective_date', 255)->nullable();
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
        Schema::dropIfExists('employee_bpjs');
    }
}
