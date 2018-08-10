<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Dividend;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DividendWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Dividend
     *
     * @var \Droplister\XcpCore\App\Dividend
     */
    public $dividend;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Dividend  $dividend
     * @return void
     */
    public function __construct(Dividend $dividend)
    {
        $this->dividend = $dividend;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('dividend-channel');
    }
}