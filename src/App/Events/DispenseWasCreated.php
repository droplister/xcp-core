<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Dispense;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DispenseWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Dispense
     *
     * @var \Droplister\XcpCore\App\Dispense
     */
    public $dispense;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Dispense  $dispense
     * @return void
     */
    public function __construct(Dispense $dispense)
    {
        $this->dispense = $dispense;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('dispense-channel');
    }
}