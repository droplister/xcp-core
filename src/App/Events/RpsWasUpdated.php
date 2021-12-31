<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Rps;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RpsWasUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Rps
     *
     * @var \Droplister\XcpCore\App\Rps
     */
    public $rps;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Rps  $rps
     * @return void
     */
    public function __construct(Rps $rps)
    {
        $this->rps = $rps;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('rps-channel');
    }
}