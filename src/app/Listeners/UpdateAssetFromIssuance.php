<?php

namespace Droplister\XcpCore\App\Listeners;

use Droplister\XcpCore\App\Asset;
use Droplister\XcpCore\App\Events\IssuanceWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateAssetFromIssuance
{
    /**
     * Handle the event.
     *
     * @param  \Droplister\XcpCore\App\Events\IssuanceWasCreated  $event
     * @return void
     */
    public function handle(IssuanceWasCreated $event)
    {
        Asset::updateAsset($event->issuance);
    }
}
