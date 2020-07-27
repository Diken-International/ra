<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldsInTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id']);
        });

        Schema::create('product_service', function (Blueprint $table){
           $table->integer('product_id')->unsigned();
           $table->integer('service_id')->unsigned();
           $table->foreign('product_id')->references('id')->on('products');
           $table->foreign('service_id')->references('id')->on('services');
        });

        Schema::create('product_user', function (Blueprint $table){
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('status')->default(true);
            $table->string('product_type')->default('own'); // own, borrowed
            $table->integer('period_service')->default(30);
            $table->date('next_service')->default(\Carbon\Carbon::now()->addDay(30));
            $table->date('last_service')->default(\Carbon\Carbon::now());
            $table->string('serial_number');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('user_id')->references('id')->on('users');
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
        //
    }
}
