<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReviewClient extends Mailable
{
    use Queueable, SerializesModels;

    public $reportServices;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($reportServices)
    {
        //
        $this->reportServices = $reportServices;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.demo');
    }
}
