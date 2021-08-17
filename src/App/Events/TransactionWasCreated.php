<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TransactionWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Transaction
     *
     * @var \Droplister\XcpCore\App\Transaction
     */
    public $transaction;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Transaction  $transaction
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('transaction-channel');
    }
}