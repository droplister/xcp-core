<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Postqueue;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostqueueWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Postqueue
     *
     * @var \Droplister\XcpCore\App\Postqueue
     */
    public $postqueue;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Postqueue  $postqueue
     * @return void
     */
    public function __construct(Postqueue $postqueue)
    {
        $this->postqueue = $postqueue;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('postqueue-channel');
    }
}