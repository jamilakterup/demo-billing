<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanPaymentCalendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payment_calenders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('loan_id');
            $table->integer('instalment_id')->nullable();
            $table->date('payment_date');
            $table->integer('number');
            $table->decimal('amount',11,2);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('loan_payment_calenders');
    }
}
