<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Execution;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ExecutionWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Execution
     *
     * @var \Droplister\XcpCore\App\Execution
     */
    public $execution;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Execution  $execution
     * @return void
     */
    public function __construct(Execution $execution)
    {
        $this->execution = $execution;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('execution-channel');
    }
}