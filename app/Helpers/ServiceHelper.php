<?php

namespace App\Helpers;
use App\Models\Reports;
use App\Models\ReportService;

class ServiceHelper
{
    /**
     * @param $service_id
     */
    public static function checkServiceComplete($service_id){

        $complete = true;
        $entities = Reports::where('services_id', $service_id)->get();
        foreach ($entities as $entity){
            if($entity->report_status != 'terminado'){
                $complete = false;
            }
        }
        return $complete;
    }
}
