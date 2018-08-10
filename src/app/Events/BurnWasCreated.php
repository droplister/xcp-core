<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Burn;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BurnWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Burn
     *
     * @var \Droplister\XcpCore\App\Burn
     */
    public $burn;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Burn  $burn
     * @return void
     */
    public function __construct(Burn $burn)
    {
        $this->burn = $burn;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('burn-channel');
    }
}