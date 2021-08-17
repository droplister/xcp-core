<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\BetExpiration;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BetExpirationWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * BetExpiration
     *
     * @var \Droplister\XcpCore\App\BetExpiration
     */
    public $bet_expiration;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\BetExpiration  $bet_expiration
     * @return void
     */
    public function __construct(BetExpiration $bet_expiration)
    {
        $this->bet_expiration = $bet_expiration;
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