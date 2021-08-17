<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Replace;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ReplaceWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Replace
     *
     * @var \Droplister\XcpCore\App\Replace
     */
    public $replace;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Replace  $replace
     * @return void
     */
    public function __construct(Replace $replace)
    {
        $this->replace = $replace;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('address-channel');
    }
}