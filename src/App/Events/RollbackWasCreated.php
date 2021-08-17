<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Rollback;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RollbackWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Rollback
     *
     * @var \Droplister\XcpCore\App\Rollback
     */
    public $rollback;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Rollback  $rollback
     * @return void
     */
    public function __construct(Rollback $rollback)
    {
        $this->rollback = $rollback;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('rollback-channel');
    }
}