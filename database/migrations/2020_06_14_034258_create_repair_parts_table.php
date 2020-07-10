<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepairPartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repair_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->integer('parts_num');
            $table->integer('number_diken');
            $table->integer('category_repair_parts_id');
            $table->foreign('category_repair_parts_id')->references('id')->on('category_repair_parts');
            $table->integer('product_repair_parts_id');
            $table->foreign('product_repair_parts_id')->references('id')->on('products_repair_parts');
            $table->string('name');
            $table->string('features');
            $table->string('quantity');
            $table->integer('number_of_part');
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
        Schema::dropIfExists('repair_parts');
    }
}
