<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Destruction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DestructionWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Destruction
     *
     * @var \Droplister\XcpCore\App\Destruction
     */
    public $destruction;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Destruction  $destruction
     * @return void
     */
    public function __construct(Destruction $destruction)
    {
        $this->destruction = $destruction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('issuance-channel');
    }
}