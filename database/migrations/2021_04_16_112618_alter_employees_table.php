<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('start_work_date')->nullable();
            $table->string('citizenship', 10);
            $table->string('citizenship_country', 50)->nullable();
            $table->string('identity_type', 50)->nullable();
            $table->string('identity_number', 100)->nullable();
            $table->date('identity_expire_date')->nullable();
            $table->string('place_of_birth', 255);
            $table->string('marital_status', 50)->nullable();
            $table->string('religion', 50)->nullable();
            $table->string('blood_type', 10)->nullable();
            $table->string('last_education', 50)->nullable();
            $table->string('last_education_name', 255)->nullable();
            $table->string('study_program', 255)->nullable();
            $table->string('emergency_contact_name', 255)->nullable();
            $table->string('emergency_contact_number', 255)->nullable();
            $table->string('attachment_cv', 255)->nullable();
            $table->string('attachment_transcripts', 255)->nullable();
            $table->string('attachment_identity', 255)->nullable();
            $table->string('attachment_other', 255)->nullable();
            $table->string('bank_account_name', 255)->nullable();
            $table->string('bank_account_owner', 255)->nullable();
            $table->string('bank_account_number', 255)->nullable();
            $table->string('bank_account_branch', 255)->nullable();
            $table->string('npwp_name', 255)->nullable();
            $table->date('npwp_start_date')->nullable();
            $table->string('npwp_number', 255)->nullable();
            $table->foreignId('npwp_tax_collector')->nullable();
            $table->string('taxpayer_status', 30)->nullable();
            $table->string('bpjs_ketenagakerjaan_number', 255)->nullable();
            $table->date('bpjs_ketenagakerjaan_effective_date')->nullable();
            $table->string('bpjs_kesehatan_number', 255)->nullable();
            $table->date('bpjs_kesehatan_effective_date')->nullable();
            // ALTER
            $table->foreignId('company_location_id')->nullable()->change();
            $table->foreignId('designation_id')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('last_name')->nullable()->change();
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
            $table->dropColumn('start_work_date');
            $table->dropColumn('citizenship');
            $table->dropColumn('citizenship_country');
            $table->dropColumn('identity_type');
            $table->dropColumn('identity_number');
            $table->dropColumn('identity_expire_date');
            $table->dropColumn('place_of_birth');
            $table->dropColumn('marital_status');
            $table->dropColumn('religion');
            $table->dropColumn('blood_type');
            $table->dropColumn('last_education');
            $table->dropColumn('last_education_name');
            $table->dropColumn('study_program');
            $table->dropColumn('emergency_contact_name');
            $table->dropColumn('emergency_contact_number');
            $table->dropColumn('attachment_cv');
            $table->dropColumn('attachment_transcripts');
            $table->dropColumn('attachment_identity');
            $table->dropColumn('attachment_other');
            $table->dropColumn('bank_account_name');
            $table->dropColumn('bank_account_owner');
            $table->dropColumn('bank_account_number');
            $table->dropColumn('bank_account_branch');
            $table->dropColumn('npwp_name');
            $table->dropColumn('npwp_start_date');
            $table->dropColumn('npwp_start_date');
            $table->dropColumn('npwp_tax_collector');
            $table->dropColumn('taxpayer_status');
            $table->dropColumn('bpjs_ketenagakerjaan_number');
            $table->dropColumn('bpjs_ketenagakerjaan_effective_date');
            $table->dropColumn('bpjs_kesehatan_number');
            $table->dropColumn('bpjs_kesehatan_effective_date');
            // ALTER
            $table->foreignId('company_location_id')->change();
            $table->foreignId('designation_id')->change();
            $table->text('address')->change();
            $table->string('last_name')->change();
        });
    }
}
