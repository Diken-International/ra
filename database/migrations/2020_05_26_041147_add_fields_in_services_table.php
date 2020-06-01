<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('services', function (Blueprint $table) {
            //
            $table->string('address');
            $table->string('postal_code');
            $table->string('state');
            $table->string('municipality');
            $table->string('contact_phone');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('services', function (Blueprint $table) {
            //
            $table->dropColumn('address');
            $table->dropColumn('postal_code');
            $table->dropColumn('state');
            $table->dropColumn('municipality');
            $table->dropColumn('contact_phone');
            
        });
    }
}
