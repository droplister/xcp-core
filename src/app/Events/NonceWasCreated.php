<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Nonce;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NonceWasUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Nonce
     *
     * @var \Droplister\XcpCore\App\Nonce
     */
    public $nonce;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Nonce  $nonce
     * @return void
     */
    public function __construct(Nonce $nonce)
    {
        $this->nonce = $nonce;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('nonce-channel');
    }
}