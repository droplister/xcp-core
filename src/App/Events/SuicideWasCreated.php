<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Suicide;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SuicideWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Suicide
     *
     * @var \Droplister\XcpCore\App\Suicide
     */
    public $suicide;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Suicide  $suicide
     * @return void
     */
    public function __construct(Suicide $suicide)
    {
        $this->contract = $suicide;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('suicide-channel');
    }
}