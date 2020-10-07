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

            $query_set = Reports::whereRaw(
                "(service_begin >= ? AND service_begin <= ?)",
                [$request->get('service_begin')." 00:00:00", $request->get('service_end')." 23:59:59"]);

            if (!empty($request->get('technical_id'))){
                $query_set = $query_set->where('technical_id', $request->get('technical_id'))->get();
            }

            if (!empty($request->get('report_status'))){
                $query_set = $query_set->where('report_status', $request->get('report_status'))->get();
            }

            $services = $query_set->get();

            return CustomResponse::success('Reporte administrativo',['services' => $services]);
        }

        if($request->current_user->role == 'tecnico'){

            $services = Reports::where('technical_id', $request->current_user->id)->get();

            return CustomResponse::success('Reporte de tecnico encontrado correctamente',['services' => $services]);
        }

        if ($request->current_user->role == 'cliente'){

            $services = Reports::where('client_id', $request->current_user->id)->get();

            return CustomResponse::success('Reporte de cliente encontrado correctamente',['services' => $services]);

        }

        return CustomResponse::success("Reporte sin datos para mostrar", ['services' => []]);


    }
}
