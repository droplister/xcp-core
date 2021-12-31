<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Mempool;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MempoolWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Mempool
     *
     * @var \Droplister\XcpCore\App\Mempool
     */
    public $mempool;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Mempool  $mempool
     * @return void
     */
    public function __construct(Mempool $mempool)
    {
        $this->mempool = $mempool;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('mempool-channel');
    }
}