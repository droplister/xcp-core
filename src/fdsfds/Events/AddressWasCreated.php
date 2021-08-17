<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Address;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AddressWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Address
     *
     * @var \Droplister\XcpCore\App\Address
     */
    public $address;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Address  $address
     * @return void
     */
    public function __construct(Address $address)
    {
        $this->address = $address;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('address-channel');
    }
}