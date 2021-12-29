<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCompanyLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_locations', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable()->change();
            $table->foreignId('employee_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_locations', function (Blueprint $table) {
            $table->foreignId('company_id')->nullable(false)->change();
            $table->foreignId('employee_id')->nullable(false)->change();
        });
    }
}
