<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Helpers\CustomResponse;
use Illuminate\Support\Facades\DB;


use App\Models\Services;

class ReportServiceController extends Controller
{
    //
    public function index(Request $request,$technical_id,$start_date,$end_date){

    	if($technical_id != null){

    		$service_rep = DB::transaction(function() use($request, $technical_id, $start_date, $end_date){
    			
    			$services_report = Services::with(['reportServices' => function($report) use($start_date) {
    				$report->where('service_start',$start_date);
    			}])->get();
				
    			//return compact('services_report');
    			
    			dd($services_report);
    		});

    		//return CustomResponse::success('Reporte Encontrado', $service_rep);
    	}
    		/*
    		$services_report = Services::where([
    			'branch_office_id'=>$request->current_user->branch_office_id
    		])->get();

    		return $services_report;
    		*/
    }
}
