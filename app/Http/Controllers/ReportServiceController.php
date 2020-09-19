<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\CustomResponse;
use App\Helpers\ModelHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Service_Reposts\ServicesReportsIndexRequest;

use App\Models\Reports;

class ReportServiceController extends Controller
{

    public function index(ServicesReportsIndexRequest $request){

        
        if($request->get('technical_id') == null){

            $service_report = Reports::whereDate('service_begin', $request->get('service_begin') )
            ->orWhereDate('service_end', $request->get('service_end') )
            ->get();
        

            return CustomResponse::success('Reporte encontrado correctamente',$service_report);
        }
        
        echo "Error";

        
    }
}
