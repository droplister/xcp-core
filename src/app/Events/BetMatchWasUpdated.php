<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\BetMatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BetMatchWasUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * BetMatch
     *
     * @var \Droplister\XcpCore\App\BetMatch
     */
    public $bet_match;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\BetMatch  $bet_match
     * @return void
     */
    public function __construct(BetMatch $bet_match)
    {
        $this->bet_match = $bet_match;
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