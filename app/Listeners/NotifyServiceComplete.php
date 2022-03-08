<?php

namespace App\Listeners;

use App\Mail\ReviewClient;
use App\Models\Services;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class NotifyServiceComplete implements ShouldQueue
{

    public $service;

    /**
     * @param $event
     * Check if not send email in other occasion send_review_email = FALSE
     */
    public function handle($event)
    {
        $this->service = Services::find($event->service_id);
        if(!$this->service->send_review_email){
            foreach ($event->client->contacts as $contact){
                // Send email to contacts
                if (filter_var($contact->email, FILTER_VALIDATE_EMAIL)){
                    Mail::to($contact->email)->send(new ReviewClient($this->service));
                }
            }
            Mail::to($event->client->email)->send(new ReviewClient($this->service));
            $this->service->send_review_email = true;
            // $this->service->save();
        }
    }

    /**
     * @param $event
     * @param $exception
     * If this function generate a exception send notification Bugsnag Service
     */
    public function failed($event, $exception){

        Bugsnag::notifyException($exception);

    }
}
