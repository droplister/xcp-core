<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Issuance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class IssuanceWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Issuance
     *
     * @var \Droplister\XcpCore\App\Issuance
     */
    public $issuance;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Issuance  $issuance
     * @return void
     */
    public function __construct(Issuance $issuance)
    {
        $this->issuance = $issuance;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('issuance-channel');
    }
}