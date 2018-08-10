<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Btcpay;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BtcpayWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Btcpay
     *
     * @var \Droplister\XcpCore\App\Btcpay
     */
    public $btcpay;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Btcpay  $btcpay
     * @return void
     */
    public function __construct(Btcpay $btcpay)
    {
        $this->btcpay = $btcpay;
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