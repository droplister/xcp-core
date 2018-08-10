<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Bet;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BetWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Bet
     *
     * @var \Droplister\XcpCore\App\Bet
     */
    public $bet;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Bet  $bet
     * @return void
     */
    public function __construct(Bet $bet)
    {
        $this->bet = $bet;
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