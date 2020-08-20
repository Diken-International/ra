<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodosView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("
        CREATE OR REPLACE VIEW  all_activities AS
        SELECT *
        FROM (
            SELECT
                   todos.id as id,
                   'todo' as type,
                   concat(u.name,' ',u.last_name) as client_name,
                   todos.technical_id as technical_id,
                   todos.date as date_activity,
                   todos.activity as activity,
                   todos.type as type_activity,
                   todos.kms as kms
            FROM todos INNER JOIN users u on todos.client_id = u.id
            UNION ALL
            SELECT services.id as id,
                   'service' as type,
                   concat(u.name,' ',u.last_name) as client_name,
                   services.technical_id as technical_id,
                   services.tentative_date as date_activity,
                   'mantenimiento' as activity,
                   services.type as type_activity,
                   services.kms as kms
            from services inner join users u on services.client_id = u.id
            ) as union_data
        order by date_activity asc");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \Illuminate\Support\Facades\DB::statement("DROP VIEW all_activities");
    }
}
