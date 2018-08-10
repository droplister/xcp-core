<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Storage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class StorageWasCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Storage
     *
     * @var \Droplister\XcpCore\App\Storage
     */
    public $storage;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Storage  $storage
     * @return void
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('storage-channel');
    }
}