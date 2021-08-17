<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\RpsMatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RpsMatchWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * RpsMatch
     *
     * @var \Droplister\XcpCore\App\RpsMatch
     */
    public $rps_match;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\RpsMatch  $rps_match
     * @return void
     */
    public function __construct(RpsMatch $rps_match)
    {
        $this->rps_match = $rps_match;
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