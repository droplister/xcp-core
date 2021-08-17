<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\RpsMatchExpiration;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RpsMatchExpirationWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * RpsMatchExpiration
     *
     * @var \Droplister\XcpCore\App\RpsMatchExpiration
     */
    public $rps_match_expiration;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\RpsMatchExpiration  $rps_match_expiration
     * @return void
     */
    public function __construct(RpsMatchExpiration $rps_match_expiration)
    {
        $this->rps_match_expiration = $rps_match_expiration;
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