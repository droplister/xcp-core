<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\OrderMatchExpiration;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderMatchExpirationWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * OrderMatchExpiration
     *
     * @var \Droplister\XcpCore\App\OrderMatchExpiration
     */
    public $order_match_expiration;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\OrderMatchExpiration  $order_match_expiration
     * @return void
     */
    public function __construct(OrderMatchExpiration $order_match_expiration)
    {
        $this->order_match_expiration = $order_match_expiration;
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