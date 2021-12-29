<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leave_settings', function (Blueprint $table) {
            $table->id();
            $table->string('period_type', 30)->nullable()->default('bersama'); // bersama || individu
            $table->string('mass_period_type_start_date', 20)->nullable()->default('01-12');
            $table->string('start_leave_type', 30)->nullable()->default('after_month_work'); // after_start_work || after_month_work
            $table->smallInteger('after_month_work_months_number')->nullable()->default(0);
            $table->string('leave_plafond_type', 20)->nullable()->default('single'); // single || multi
            $table->smallInteger('single_plafond_max_day')->nullable()->default(0);
            $table->string('leave_distribution_method', 20)->nullable()->default('standar'); // standar || permonth
            $table->string('remain_leave_method', 20)->nullable()->default('cashed'); // cashed || carry forward
            $table->smallInteger('carry_forward_max_day')->nullable()->default(0);
            $table->smallInteger('carry_forward_effective_month')->nullable()->default(0);
            $table->string('cashed_base_multiplier', 50)->nullable()->default('gaji_pokok'); // gaji_pokok || gaji_pokok_tunjangan
            $table->smallInteger('cashed_max_leave_day')->nullable()->default(0);
            $table->smallInteger('cashed_max_day_per_month')->nullable()->default(0);
            $table->tinyInteger('cashed_taxed')->nullable()->default(0); // 0 || 1
            $table->tinyInteger('has_leave_deposit')->nullable()->default(0); // 0 || 1
            $table->smallInteger('leave_deposit_max_day')->nullable()->default(0);
            $table->tinyInteger('has_block_leave')->nullable()->default(0); // 0 || 1
            $table->smallInteger('block_leave_number_of_days')->nullable()->default(0);
            $table->tinyInteger('is_mass_leave_cut_leave_plafond')->nullable()->default(1);
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
        Schema::dropIfExists('leave_settings');
    }
}
