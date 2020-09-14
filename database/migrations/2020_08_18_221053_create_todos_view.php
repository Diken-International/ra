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
        SELECT union_data.*,
       (select company_name from users where id = union_data.client_id) as client_name,
       (select name from users where id = union_data.technical_id) as technical_name
        FROM (
            SELECT
                   todos.id as id,
                   'todo' as type,
                   todos.technical_id as technical_id,
                   todos.client_id as client_id,
                   todos.date as date_activity,
                   todos.activity as activity,
                   todos.type as type_activity,
                   todos.kms as kms,
                   todos.description as description
            FROM todos
            UNION ALL
            SELECT services.id as id,
                   'service' as type,
                   services.technical_id as technical_id,
                   services.client_id as client_id,
                   services.tentative_date as date_activity,
                   services.activity as activity,
                   services.type as type_activity,
                   services.kms as kms,
                   'servicio de mantenimiento' as description
            from services
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
