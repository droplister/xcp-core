<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Order
     *
     * @var \Droplister\XcpCore\App\Order
     */
    public $order;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Order  $order
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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