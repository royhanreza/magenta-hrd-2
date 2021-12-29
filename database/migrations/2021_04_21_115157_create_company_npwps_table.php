<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyNpwpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_npwps', function (Blueprint $table) {
            $table->id();
            $table->string('company_npwp_name', 255);
            $table->string('company_npwp_number', 255);
            $table->string('leader_npwp_name', 255);
            $table->string('leader_npwp_number', 255);
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
        Schema::dropIfExists('company_npwps');
    }
}
