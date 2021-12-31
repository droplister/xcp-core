<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Debit;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DebitWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Debit
     *
     * @var \Droplister\XcpCore\App\Debit
     */
    public $debit;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Debit  $debit
     * @return void
     */
    public function __construct(Debit $debit)
    {
        $this->debit = $debit;
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