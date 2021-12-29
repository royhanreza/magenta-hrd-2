<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetNullableEndOf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->date('end_of_employement_date')->nullable()->change();
            $table->date('end_of_employee_status_reminder')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->date('end_of_employement_date')->nullable(false)->change();
            $table->date('end_of_employee_status_reminder')->nullable(false)->change();
        });
    }
}
