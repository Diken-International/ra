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

        
        if($request->current_user->role == 'admin'){
        	
            $services = Reports::whereBetween('service_begin',[ $request->get('service_begin'), $request->get('service_end')])->get();

            return CustomResponse::success('Reporte encontrado correctamente',$services);
        }
        
            $services = Reports::where('technical_id', $request->current_user->id)->get();

            return CustomResponse::success('Reporte de tecnico encontrado correctamente',$services);

       

        
    }
}
