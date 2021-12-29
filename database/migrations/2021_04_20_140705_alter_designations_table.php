<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDesignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_designations', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->change();
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
        Schema::table('company_designations', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable(false)->change();
            $table->foreignId('added_by')->nullable(false)->change();
        });
    }
}
