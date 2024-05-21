<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('estimate_id');
            $table->integer('number');
            $table->integer('invoice_type_id');
            $table->integer('ref_number')->nullable();
            $table->integer('customer_id');
            $table->integer('employee_id');
            $table->integer('user_id');
            $table->date('date');
            $table->date('expected_payment_date')->nullable();
            $table->text('subject',500)->nullable();
            $table->text('description',1000)->nullable();
            $table->tinyInteger('date_visibility');
            $table->tinyInteger('auto_seal_signature')->default(1);
            $table->string('vat_text_visibility',255);
            $table->text('note')->nullable();
            $table->decimal('sub_total',12,2);
            $table->decimal('discount',12,2);
            $table->decimal('vat',12,2);
            $table->decimal('tax',12,2);
            $table->decimal('total',12,2);
            $table->tinyInteger('status');
            $table->tinyInteger('is_recurring',2)->default(0);
            $table->integer('recurring_interval')->default(0);
            $table->date('recurring_start_date')->nullable();
            $table->string('file',255)->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
