<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->date('date_of_filing');
            $table->foreignId('employee_id');
            $table->foreignId('permission_category_id');
            $table->text('permission_dates');
            $table->smallInteger('number_of_days');
            $table->string('attachment', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('status', 255)->nullable()->default('pending');
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
        Schema::dropIfExists('permissions');
    }
}
