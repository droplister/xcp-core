<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\OrderExpiration;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderExpirationWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * OrderExpiration
     *
     * @var \Droplister\XcpCore\App\OrderExpiration
     */
    public $order_expiration;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\OrderExpiration  $order_expiration
     * @return void
     */
    public function __construct(OrderExpiration $order_expiration)
    {
        $this->order_expiration = $order_expiration;
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