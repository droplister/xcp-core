<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\BetMatchResolution;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BetMatchResolutionWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * BetMatchResolution
     *
     * @var \Droplister\XcpCore\App\BetMatchResolution
     */
    public $bet_match_resolution;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\BetMatchResolution  $bet_match_resolution
     * @return void
     */
    public function __construct(BetMatchResolution $bet_match_resolution)
    {
        $this->bet_match_resolution = $bet_match_resolution;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('bet-channel');
    }
}