<?php

namespace Droplister\XcpCore\App\Listeners;

use Droplister\XcpCore\App\Order;
use Droplister\XcpCore\App\Events\BlockWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExpireAndFillOrders
{
    /**
     * Handle the event.
     *
     * @param  \Droplister\XcpCore\App\Events\BlockWasCreated  $event
     * @return void
     */
    public function handle(BlockWasCreated $event)
    {
        // Catch Failed Message Updates
        $filled = Order::where('status', '=', 'open')
            ->where('expire_index', '<=', $event->block->block_index)
            ->where('get_remaining', '<=', 0)
            ->orWhere('status', '=', 'open')
            ->where('expire_index', '<=', $event->block->block_index)
            ->where('give_remaining', '<=', 0)
            ->update(['status' => 'filled']);

        // Catch Failed Message Updates
        $expired = Order::where('status', '=', 'open')
            ->where('expire_index', '<=', $event->block->block_index)
            ->orWhere('status', '=', 'open')
            ->where('expire_index', '<=', $event->block->block_index)
            ->update(['status' => 'expired']);
    }
}
