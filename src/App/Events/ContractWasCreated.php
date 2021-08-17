<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Contract;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ContractWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Contract
     *
     * @var \Droplister\XcpCore\App\Contract
     */
    public $contract;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Contract  $contract
     * @return void
     */
    public function __construct(Contract $contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('contract-channel');
    }
}