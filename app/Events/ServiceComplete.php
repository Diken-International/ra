<?php

namespace App\Events;

use App\Models\Services;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ServiceComplete
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $service_id;
    public $client;

    public function __construct($service_id, User $client)
    {
        $this->service_id   = $service_id;
        $this->client       = $client;
    }
}
