<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValueEmployeeBpjs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_bpjs', function (Blueprint $table) {
            $table->integer('wage')->nullable()->default(0);
            $table->decimal('jkk_company_percentage', $precision = 8, $scale = 2)->nullable()->default(0.89);
            $table->decimal('jkk_personal_percentage', $precision = 8, $scale = 2)->nullable()->default(0);
            $table->decimal('jkm_company_percentage', $precision = 8, $scale = 2)->nullable()->default(0.3);
            $table->decimal('jkm_personal_percentage', $precision = 8, $scale = 2)->nullable()->default(0);
            $table->decimal('jht_company_percentage', $precision = 8, $scale = 2)->nullable()->default(3.7);
            $table->decimal('jht_personal_percentage', $precision = 8, $scale = 2)->nullable()->default(2);
            $table->decimal('jp_company_percentage', $precision = 8, $scale = 2)->nullable()->default(2);
            $table->decimal('jp_personal_percentage', $precision = 8, $scale = 2)->nullable()->default(1);
            $table->decimal('kesehatan_company_percentage', $precision = 8, $scale = 2)->nullable()->default(4);
            $table->decimal('kesehatan_personal_percentage', $precision = 8, $scale = 2)->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_bpjs', function (Blueprint $table) {
            //
        });
    }
}
