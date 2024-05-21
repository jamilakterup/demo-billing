<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->string('name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('spouse_name');
            $table->string('occupation');
            $table->decimal('monthly_income',11,2);
            $table->string('education');
            $table->date('dob');
            $table->enum('gender',['Male','Female','Other']);

            $table->string('phone');
            $table->string('email');
            $table->string('nid');

            $table->string('vill');
            $table->string('post');
            $table->string('ps');
            $table->string('dist');

            $table->string('photo');

            $table->timestamps();
        });

        Schema::table('members', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('members');
    }
}
