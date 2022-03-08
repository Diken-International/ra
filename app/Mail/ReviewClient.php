<?php

namespace App\Mail;

use App\Models\Reports;
use App\Models\ReportService;
use App\Models\Services;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ReviewClient extends Mailable
{
    use Queueable, SerializesModels;

    public $service;
    public $service_reports;
    public $client;
    public $url;
    public $url_progress;


    public function __construct($service)
    {
        $this->service = $service;
        $this->getAllReports();
    }

    public function getAllReports(){
        $this->client = User::find($this->service->client_id);
        $this->service_reports = DB::table('services')->select([
            'p.name as product_name',
            'services.id as service_id',
            'services.activity as service_activity',
            'services.type as service_type',
            'services.created_at as service_start',
            'pu.period_service as product_period_service'])
            ->join('report_services as rs', 'services.id', 'rs.service_id')
            ->join('product_user as pu', 'rs.product_user_id', 'pu.id')
            ->join('products as p', 'pu.product_id', 'p.id')
            ->where([
                'p.branch_office_id' => $this->client->branch_office_id,
                'services.id' => $this->service->id
            ])->get();
        $this->url = env('APP_FRONTEND')."/review/".$this->service->id.'/?token='.Crypt::encrypt($this->client->email);
        $this->url_progress = route('download.report',['service_id' => $this->service->id]).'?download=1';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.notify.complete')
            ->subject("diken - Información sobre tu servicio");
    }
}
