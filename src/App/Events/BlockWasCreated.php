<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Block;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BlockWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Block
     *
     * @var \Droplister\XcpCore\App\Block
     */
    public $block;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Block  $block
     * @return void
     */
    public function __construct(Block $block)
    {
        $this->block = $block;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('block-channel');
    }
}