<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\BetMatchExpiration;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BetMatchExpirationWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * BetMatchExpiration
     *
     * @var \Droplister\XcpCore\App\BetMatchExpiration
     */
    public $bet_match_expiration;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\BetMatchExpiration  $bet_match_expiration
     * @return void
     */
    public function __construct(BetMatchExpiration $bet_match_expiration)
    {
        $this->bet_match_expiration = $bet_match_expiration;
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