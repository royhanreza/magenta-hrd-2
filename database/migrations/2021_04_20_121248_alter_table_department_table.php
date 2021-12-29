<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_departments', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->change();
            $table->foreignId('employee_id')->nullable()->change();
            $table->foreignId('company_location_id')->nullable()->change();
            $table->foreignId('added_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_departments', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable(false)->change();
            $table->foreignId('employee_id')->nullable(false)->change();
            $table->foreignId('company_location_id')->nullable(false)->change();
            $table->foreignId('added_by')->nullable(false)->change();
        });
    }
}
