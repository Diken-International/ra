<?php

namespace App\Http\Controllers;

use App\Helpers\ModelHelper;
use App\Http\Requests\Downloads\ReceptionRequest;
use App\Models\Activities;
use App\Models\ProductUser;
use App\Models\ReportService;
use App\Models\Services;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
class LettersController extends Controller
{

    public $functionality = [
        'e' => 'Excelente',
        'b' => 'Buena',
        'r' => 'Regular'
    ];

    private function translate($value){
        return $this->functionality[$value];
    }
    public function reception(ReceptionRequest $request){

        $product_user = ModelHelper::findEntity(ProductUser::class, $request->get('product_user'));
        $services = $request->get('services');
        $functionality = $this->translate($request->get('functionality'));
        $foam = $this->translate($request->get('foam'));
	    $pdf = PDF::loadView('formats.reception', [
	        'product_user' => $product_user,
            'services' => $services,
            'functionality' => $functionality,
            'foam' => $foam
        ]);

        return base64_encode($pdf->output());
	}

    public function warranty(Request $request){

        $pdf = PDF::loadView('formats.warranty');

        return base64_encode($pdf->output());
    }

    public function planWeek(Request $request){


        $todo_week = Activities::where([
            'technical_id'  =>  $request->current_user->id
        ])->whereRaw(
            "(date_activity >= ? AND date_activity <= ?)",
            [$request->get('start_week')." 00:00:00", $request->get('end_week')." 23:59:59"])->get();


        setlocale(LC_TIME, 'es_ES');


        $activities_week = $todo_week->map(function ($todo){
            $todo->date = (new \DateTime($todo->date_activity))->format('Y-m-d');
            return $todo;
        })->groupBy('date');

        $sum_kms = $todo_week->reduce(function ($sum, $todo){
            return $sum + $todo->kms;
        });

        $sum_performance = $todo_week->reduce(function ($sum, $todo){
            return $sum + $todo->performance;
        });

        $start_day = $request->get('start_week', '2020-08-19');
        $end_day = $request->get('end_week', '2020-08-27');

        $technical = User::where('id', $request->current_user->id)->first();

        $div = 1;
        if ($todo_week->count() >= 1){
            $div = $todo_week->count();
        }

        $pdf = PDF::loadView('formats.activities_week', [
            'activities_week' => $activities_week,
            'technical_name' => $technical->name.' '.$technical->last_name,
            'range' => [
                'start' => strftime("%A, %d %B %G", strtotime(Carbon::parse($start_day))),
                'end'   => strftime("%A, %d %B %G", strtotime(Carbon::parse($end_day)))
            ],
            'sum_kms' => $sum_kms,
            'sum_performance' => ($sum_performance / $div)
        ]);

        $pdf->setPaper('A4', 'landscape');

        return base64_encode($pdf->output());


    }

    public function reportService(Request $request, $service_id){
        $service = Services::with(['client', 'technical', 'reportServices'])->where(['id' =>  $service_id])->first();

        // debug view
        // return view('formats.reports',['service' => $service]);

        $pdf = PDF::loadView('formats.reports', [
            'service' => $service
        ]);

        if ($request->get('download') == 1){
            return $pdf->download('report.pdf');
        }
        return base64_encode($pdf->output());
    }

}
