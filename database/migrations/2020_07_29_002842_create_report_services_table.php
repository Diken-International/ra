<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_services', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id')->unsigned();
            $table->integer('product_user_id')->unsigned();
            $table->json('costs')->default("[]");
            $table->json('costs_repairs')->default("[]");
            $table->float('subtotal')->nullable();
            $table->float('total')->nullable();
            $table->integer('progress')->default(0);
            $table->string('description')->nullable();
            $table->string('status')->default('pendiente');
            $table->string('dilution')->nullable();
            $table->string('frequency')->nullable();
            $table->string('method')->nullable();
            $table->date('service_end')->nullable();
            $table->date('service_start');
            $table->foreign('service_id')->references('id')->on('services');
            $table->foreign('product_user_id')->references('id')->on('product_user');
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
        Schema::dropIfExists('report_services');
    }
}
