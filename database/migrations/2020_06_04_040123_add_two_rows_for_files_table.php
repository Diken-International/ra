<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoRowsForFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('files', function (Blueprint $table) {
            //
            $table->string('category')->default('');
            $table->string('type');

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
        Schema::table('files', function (Blueprint $table) {
            //
            $table->dropColumn('category')->default('');
            $table->dropColumn('type');

        });
    }
}
