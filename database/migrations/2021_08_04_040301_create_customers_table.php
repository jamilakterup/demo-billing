<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->text('name', 255);
            $table->text('display_name', 255);
            $table->string('email')->index();
            $table->string('phone', 20);
            $table->string('company_name', 255);
            $table->string('company_email', 255)->nullable();
            $table->string('company_phone', 20)->nullable();
            $table->string('company_address', 255);
            $table->string('company_website', 255);
            $table->string('company_logo', 255)->nullable();
            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('customers');
    }
}
