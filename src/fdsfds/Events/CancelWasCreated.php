<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Cancel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CancelWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Cancel
     *
     * @var \Droplister\XcpCore\App\Cancel
     */
    public $cancel;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Cancel  $cancel
     * @return void
     */
    public function __construct(Cancel $cancel)
    {
        $this->cancel = $cancel;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('order-channel');
    }
}