<?php


namespace App\Helpers;


use App\Models\Reports;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportHelper
{

    public static $translate_fields = [
        'product_serial_number' => 'Número de serie del producto',
        'service_progress' => 'Progreso del servicio',
        'client_name' => 'Nombre del cliente',
        'technical_name' => 'Nombre del tecnico',
        'services_type' => 'Tipos de servicios',
        'activity_type' => 'Tipo de actividad',
        'report_number' => 'Número de reporte',
        'report_status' => 'Estatus del reporte',
        'service_begin' => 'Fecha de inicio',
        'service_end' => 'Fecha de termino',
        'extra_total_costs' => 'Costos extras',
        'repairs_total_costs' => 'Costos de refacciones',
        'number_services' => 'Número de servicios',
    ];

    public static function ReportServices($query_set, $request, $paginator = true){

        $query_set = $query_set::whereRaw(
            "(service_begin >= ? AND service_begin <= ?)",
            [$request->get('service_begin')." 00:00:00", $request->get('service_end')." 23:59:59"]);

        if (!empty($request->get('report_status'))){
            $query_set = $query_set->where('report_status', $request->get('report_status'));
        }

        if (!empty($request->get('product_serial_number'))){
            $query_set = $query_set->where('product_serial_number', $request->get('product_serial_number'));
        }

        if (!empty($request->get('technical_id'))){
            $query_set = $query_set->where('technical_id', $request->get('technical_id'));
        }

        if (!empty($request->get('client_id'))){
            $query_set = $query_set->where('client_id', $request->get('client_id'));
        }
        /*
         * clone query to use in others moments
         */
        $q1 = clone $query_set;
        $q2 = clone $query_set;
        $q3 = clone $query_set;

        $finished_services= $q1->where('report_status', 'terminado')->count();
        $pending_services= $q2->where('report_status', 'pendiente')->count();
        $in_process_services= $q3->where('report_status', 'en_proceso')->count();

        if ($paginator){
            $services = PaginatorHelper::create($query_set->get(), $request);
        }else{
            $services = $query_set->get();
        }

        return compact('finished_services', 'pending_services', 'in_process_services', 'services');
    }

    public static function createExcel($report){
        $writer = null;
        $titles = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        $spreadsheet = new Spreadsheet();
        $date = Carbon::now()->format('d M Y');
        $spreadsheet->getProperties()
            ->setCreator("Diken CSS APP")
            ->setTitle("Diken CSS APP - Reportes")
            ->setDescription(
                "Este reporte fue generado el día " . $date
            );
        $spreadsheet->getActiveSheet()->getStyle('A1:M1')->applyFromArray($titles);
        $spreadsheet->getActiveSheet()->getStyle('A')->applyFromArray($titles);
        $spreadsheet->getActiveSheet()->mergeCells('A1:M1');
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValueByColumnAndRow(1, 1, "Este reporte fue generado el día ". $date);

        $count = 1;
        foreach (ReportHelper::$translate_fields as $key => $value){
            $sheet->setCellValueByColumnAndRow($count, 3, $value);
            $count++;
        }

        $row = 4;
        foreach ($report['services'] as $service){
            $column  = 1;
            foreach (ReportHelper::$translate_fields as $key2 => $value){
                $sheet->setCellValueByColumnAndRow($column, $row, $service->$key2);
                $column++;
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        return $writer;
    }
}
