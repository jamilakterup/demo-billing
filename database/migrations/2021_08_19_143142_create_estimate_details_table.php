<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimateDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_details', function (Blueprint $table) {
            $table->id();
            $table->integer('estimate_id');
            $table->integer('product_id');
            $table->integer('quantity');
            $table->decimal('price',12,2);
            $table->decimal('total',12,2);
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
        Schema::dropIfExists('estimate_details');
    }
}
