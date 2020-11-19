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

class ReviewClient extends Mailable
{
    use Queueable, SerializesModels;

    public $service;
    public $service_reports;
    public $client;
    public $url;


    public function __construct($service)
    {
        $this->service = $service;
        $this->getAllReports();
    }

    public function getAllReports(){
        $this->service_reports = Reports::where('services_id', $this->service->id)->get();
        $this->client = User::find($this->service->client_id);
        $this->url = env('APP_FRONTEND')."/review/".$this->service->id.'/?token='.Crypt::encrypt($this->client->email);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.demo')->subject("diken - Información sobre tu servicio");
    }
}
