<?php

namespace Droplister\XcpCore\App\Listeners;

use Droplister\XcpCore\App\Asset;
use Droplister\XcpCore\App\Events\AssetWasUpdated;
use Droplister\XcpCore\App\Jobs\UpdateEnhancedAssetInfo;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateEnhancedAssetInfoAfterUpdate
{
    /**
     * Handle the event.
     *
     * @param  \Droplister\XcpCore\App\Events\AssetWasUpdated  $event
     * @return void
     */
    public function handle(AssetWasUpdated $event)
    {
        UpdateEnhancedAssetInfo::dispatch($event->asset);
    }
}
