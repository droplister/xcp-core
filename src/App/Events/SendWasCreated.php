<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Send;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SendWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Send
     *
     * @var \Droplister\XcpCore\App\Send
     */
    public $send;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Send  $send
     * @return void
     */
    public function __construct(Send $send)
    {
        $this->send = $send;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('send-channel');
    }
}