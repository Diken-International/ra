<?php

namespace App\Http\Controllers;

use App\Helpers\ReportHelper;
use App\Helpers\PaginatorHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\CustomResponse;
use App\Http\Requests\Service_Reposts\ServicesReportsIndexRequest;

use App\Models\Services;
use App\Models\Reports;

class ReportServiceController extends Controller
{

    public function index(ServicesReportsIndexRequest $request){

        $query_set = Reports::class;

        if($request->current_user->role == 'admin'){

            return $this->responseGeneric($query_set, $request);
        }

        if($request->current_user->role == 'tecnico'){

            // $query_set = $query_set->where('technical_id', $request->current_user->id);
            $request->merge(["technical_id" => $request->current_user->id]);

            return $this->responseGeneric($query_set, $request);
        }

        if ($request->current_user->role == 'cliente'){

            //$query_set = $query_set->where('client_id', $request->current_user->id);
            $request->merge(["client_id" => $request->current_user->id]);

            return $this->responseGeneric($query_set, $request);

        }

        return CustomResponse::success("Reporte sin datos para mostrar", ['services' => []]);


    }

    private function responseGeneric($query_set, $request){

        if (!empty($request->get('download'))){
            $report = ReportHelper::ReportServices($query_set, $request, false);
            $writer = ReportHelper::createExcel($report);
            $writer->save($request->current_user->id.'_report_services.xlsx');
            return response()->download($request->current_user->id.'_report_services.xlsx')->deleteFileAfterSend();

        }

        $report = ReportHelper::ReportServices($query_set, $request, true);
        return CustomResponse::success('Reporte administrativo',$report);
    }
}
