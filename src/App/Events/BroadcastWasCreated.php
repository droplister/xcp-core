<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Broadcast;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BroadcastWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Broadcast
     *
     * @var \Droplister\XcpCore\App\Broadcast
     */
    public $broadcast;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Broadcast  $broadcast
     * @return void
     */
    public function __construct(Broadcast $broadcast)
    {
        $this->broadcast = $broadcast;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('broadcast-channel');
    }
}