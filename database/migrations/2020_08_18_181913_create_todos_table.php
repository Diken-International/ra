<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('description')->nullable();
            $table->string('type'); // remote or in installations
            $table->string('activity'); // type of activity
            $table->dateTime('date');
            $table->integer('kms');
            $table->integer('technical_id');
            $table->integer('client_id')->nullable();
            $table->foreign('technical_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('users');
            $table->string('status');
            $table->softDeletes();
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
        Schema::dropIfExists('todos');
    }
}
