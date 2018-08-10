<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Balance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BalanceWasUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Balance
     *
     * @var \Droplister\XcpCore\App\Balance
     */
    public $balance;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Balance  $balance
     * @return void
     */
    public function __construct(Balance $balance)
    {
        $this->balance = $balance;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('balance-channel');
    }
}