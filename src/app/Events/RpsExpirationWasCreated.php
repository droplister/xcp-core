<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\RpsExpiration;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RpsExpirationWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * RpsExpiration
     *
     * @var \Droplister\XcpCore\App\RpsExpiration
     */
    public $rps_expiration;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\RpsExpiration  $rps_expiration
     * @return void
     */
    public function __construct(RpsExpiration $rps_expiration)
    {
        $this->rps_expiration = $rps_expiration;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('rps-channel');
    }
}