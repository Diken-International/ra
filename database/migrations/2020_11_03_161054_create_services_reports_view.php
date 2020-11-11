<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesReportsView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("
            CREATE OR REPLACE VIEW  services_reports AS
select (select sum(cost_sum::float)
        from (select r.id, elem ->> 'cost' as cost_sum
              from report_services r,
                   json_array_elements(costs) elem) as rep
        where report_services.id = rep.id
        group by rep.id
       )                                            as extra_total_costs,

       (select sum(total_cost::float)
        from (select re.id, elemen ->> 'cost' as total_cost
              from report_services re,
                   json_array_elements(costs_repairs) elemen) as total
        where report_services.id = total.id
        group by total.id
       )                                            as repairs_total_cost,

       (select count(*) from services)              as number_services,

       users.company_name                           as client_name,
       (select concat(technical.name, ' ', technical.last_name)
        from users as technical
        where technical.id = services.technical_id) as technical_name,
       services.type                                as services_type,
       services.activity                            as activity_type,
       report_services.id                           as report_number,
       report_services.status                       as report_status,
       report_services.service_start                as service_begin,
       report_services.service_end                  as service_end,
       report_services.progress                     as service_progress,
       services.technical_id                        as technical_id,
       product_user.serial_number                   as product_serial_number,
       product_user.last_service                    as product_last_service,
       users.id                                     as client_id
from services
         INNER JOIN report_services
                    ON report_services.service_id = services.id
         INNER JOIN product_user
                    ON product_user.id = report_services.product_user_id
         INNER JOIN users
                    ON users.id = product_user.user_id
         INNER JOIN branch_offices
                    ON branch_offices.id = users.branch_office_id
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services_reports_view');
    }
}
