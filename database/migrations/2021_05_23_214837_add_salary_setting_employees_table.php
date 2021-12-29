<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSalarySettingEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('daily_money_regular')->nullable()->default(0);
            $table->integer('daily_money_holiday')->nullable()->default(0);
            $table->integer('overtime_pay_regular')->nullable()->default(0);
            $table->integer('overtime_pay_holiday')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['daily_money_regular', 'daily_money_holiday', 'overtime_pay_regular', 'overtime_pay_holiday']);
        });
    }
}
