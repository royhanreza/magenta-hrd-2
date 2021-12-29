<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBpjsKetenagakerjaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bpjs_ketenagakerjaans', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('has_bpjs_ketenagakerjaan')->nullable()->default(0);
            $table->string('npp', 255)->nullable();
            $table->string('base_multiplier', 100)->nullable()->default('gaji_pokok');
            $table->tinyInteger('is_compare_salary_ump')->nullable()->default(0);
            $table->string('jkk', 20)->nullable()->default('0.24');
            $table->string('jkm', 20)->nullable()->default('0.30');
            $table->string('jht_company', 20)->nullable()->default('0.30');
            $table->string('jht_employee', 20)->nullable()->default('2.00');
            $table->tinyInteger('is_jht_company_pph')->nullable()->default(0);
            $table->tinyInteger('has_jp')->nullable()->default(0);
            $table->tinyInteger('is_jp_pph')->nullable()->default(0);
            $table->string('jp_company', 20)->nullable()->default('2');
            $table->string('jp_employee', 20)->nullable()->default('1.00');
            $table->string('max_jp_multiplier', 20)->nullable()->default('8754600');
            $table->tinyInteger('is_foreigner_has_jp')->nullable()->default(0);
            $table->tinyInteger('is_old_employee_has_jp')->nullable()->default(0);
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
        Schema::dropIfExists('bpjs_ketenagakerjaans');
    }
}
