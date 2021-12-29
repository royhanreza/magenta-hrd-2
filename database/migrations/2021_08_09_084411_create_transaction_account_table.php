<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_account', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->bigInteger('amount');
            $table->string('description', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('type', 100);
            $table->integer('account_id');
            $table->string('transaction_id', 100)->nullable();
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
        Schema::dropIfExists('transaction_account');
    }
}
