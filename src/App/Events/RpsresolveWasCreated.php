<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Rpsresolve;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RpsresolveWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Rpsresolve
     *
     * @var \Droplister\XcpCore\App\Rpsresolve
     */
    public $rpsresolve;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Rpsresolve  $rpsresolve
     * @return void
     */
    public function __construct(Rpsresolve $rpsresolve)
    {
        $this->rpsresolve = $rpsresolve;
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