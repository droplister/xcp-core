<?php

namespace Droplister\XcpCore\App\Events;

use Droplister\XcpCore\App\Asset;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AssetWasUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Asset
     *
     * @var \Droplister\XcpCore\App\Asset
     */
    public $asset;

    /**
     * Create a new event instance.
     * 
     * @param  \Droplister\XcpCore\App\Asset  $asset
     * @return void
     */
    public function __construct(Asset $asset)
    {
        $this->asset = $asset;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('asset-channel');
    }
}