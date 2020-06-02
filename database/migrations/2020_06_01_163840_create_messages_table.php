<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('message');
            $table->integer('author_id');
            $table->foreign('author_id')->references('id')->on('users');
            $table->integer('branch_office_id');
            $table->foreign('branch_office_id')->references('id')->on('branch_offices');
            $table->integer('priority');
            $table->integer('services_id');
            $table->foreign('services_id')->references('id')->on('services');
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
        Schema::dropIfExists('messages');
    }
}
