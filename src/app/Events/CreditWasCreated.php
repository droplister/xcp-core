<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Credit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CreditWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Credit
     *
     * @var \Droplister\XcpCore\App\Credit
     */
    public $credit;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Credit  $credit
     * @return void
     */
    public function __construct(Credit $credit)
    {
        $this->credit = $credit;
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