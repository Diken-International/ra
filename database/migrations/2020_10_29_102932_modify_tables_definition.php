<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTablesDefinition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repair_parts', function (Blueprint $table) {
            $table->text('features')->change();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->text('specifications_desing')->change();
            $table->text('specifications_operation')->change();
            $table->text('benefits')->change();
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
