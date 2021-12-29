<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBpjsKesehatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bpjs_kesehatans', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('has_bpjs_kesehatan')->nullable()->default(0);
            $table->string('business_code', 255)->nullable();
            $table->string('company_percentage', 20)->nullable()->default('5');
            $table->string('employee_percentage', 20)->nullable()->default('0');
            $table->string('base_multiplier', 100)->nullable()->default('gaji_pokok');
            $table->string('max_multiplier', 20)->nullable()->default('12000000');
            $table->date('effective_date')->nullable();
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
        Schema::dropIfExists('bpjs_kesehatans');
    }
}
