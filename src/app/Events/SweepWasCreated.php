<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Sweep;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddressWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Sweep
     *
     * @var \Droplister\XcpCore\App\Sweep
     */
    public $sweep;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Sweep  $sweep
     * @return void
     */
    public function __construct(Sweep $sweep)
    {
        $this->sweep = $sweep;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('sweep-channel');
    }
}