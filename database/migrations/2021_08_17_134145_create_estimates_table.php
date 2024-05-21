<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimates', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('number');
            $table->integer('invoice_type_id');
            $table->integer('ref_number')->nullable();
            $table->integer('customer_id');
            $table->integer('employee_id');
            $table->date('estimate_date');
            $table->date('expiry_date')->nullable();
            $table->text('note')->nullable();
            $table->decimal('sub_total',12,2);
            $table->decimal('discount',12,2);
            $table->decimal('vat',12,2);
            $table->decimal('tax',12,2);
            $table->decimal('total',12,2);
            $table->string('file',255)->nullable();
            $table->enum('status', ['draft', 'sent','accepted','rejected','converted'])->default('draft');
            $table->Enum('status')->default(0);
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
        Schema::dropIfExists('estimates');
    }
}
