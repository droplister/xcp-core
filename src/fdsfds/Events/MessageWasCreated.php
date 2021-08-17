<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Message
     *
     * @var \Droplister\XcpCore\App\Message
     */
    public $message;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Message  $message
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('message-channel');
    }
}