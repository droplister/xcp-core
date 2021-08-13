<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Dispenser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DispenserWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Dispenser
     *
     * @var \Droplister\XcpCore\App\Dispenser
     */
    public $dispenser;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Dispenser  $dispenser
     * @return void
     */
    public function __construct(Dispenser $dispenser)
    {
        $this->dispenser = $dispenser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('dispenser-channel');
    }
}