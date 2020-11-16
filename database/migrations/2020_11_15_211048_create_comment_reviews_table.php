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
            $table->string('token_review', 100)->nullable(false);
            $table->integer('star');
            $table->string('description', 100);
            $table->boolean('check_revision')->default(false);
            $table->integer('service_id');
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
        Schema::dropIfExists('comment_reviews');
    }
}
