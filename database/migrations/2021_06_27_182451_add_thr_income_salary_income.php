<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThrIncomeSalaryIncome extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salary_incomes', function (Blueprint $table) {
            $table->tinyInteger('thr_income')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salary_incomes', function (Blueprint $table) {
            $table->dropColumn(['thr_income']);
        });
    }
}
