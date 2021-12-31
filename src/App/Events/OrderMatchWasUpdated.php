<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\OrderMatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderMatchWasUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * OrderMatch
     *
     * @var \Droplister\XcpCore\App\OrderMatch
     */
    public $order_match;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\OrderMatch  $order_match
     * @return void
     */
    public function __construct(OrderMatch $order_match)
    {
        $this->order_match = $order_match;
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