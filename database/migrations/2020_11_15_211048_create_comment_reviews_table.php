<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('star');
            $table->text('description');
            $table->boolean('check_revision')->default(false);
            $table->integer('service_id');
            $table->timestamps();
            $table->foreign('service_id')->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_reviews');
    }
}
