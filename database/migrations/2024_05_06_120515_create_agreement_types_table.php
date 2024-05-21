<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgreementTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agreement_types', function (Blueprint $table) {
            $table->id();
            $table->string('agreement_type_name', 255)->unique();
            $table->string('agreement_type_short_name', 255);
            $table->string('subject', 255);
            $table->integer('employee_id');
            $table->text('description', 500);
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
        Schema::dropIfExists('agreement_types');
    }
}
