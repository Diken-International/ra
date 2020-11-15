<?php

namespace App\Helpers;
use App\Models\ReportService;

class ServiceHelper
{
    /**
     * @param $service_id
     */
    public static function checkServiceComplete($service_id){

        $entity = ReportService::where('service_id','=',$service_id)->get();

        return $entity;
    }
}
