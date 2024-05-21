<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cp_lead_id');
            $table->string('consultant')->nullable();
            $table->string('comment')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('followup')->nullable();
            $table->dateTime('date')->nullable();
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
        Schema::dropIfExists('cp_statuses');
    }
}
