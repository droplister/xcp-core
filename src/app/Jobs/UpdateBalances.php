<?php

namespace Droplister\XcpCore\App\Jobs;

use Droplister\XcpCore\App\Block;
use Droplister\XcpCore\App\Debit;
use Droplister\XcpCore\App\Credit;
use Droplister\XcpCore\App\Jobs\UpdateBalance;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBalances implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Update Balances Job
    |--------------------------------------------------------------------------
    |
    | The purpose of this job is to take a given block and determine which
    | addresses have had debits or credits and summarize them in such a
    | way that we only calculate the current balance once per asset.
    |
    */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Block
     *
     * @var \Droplister\XcpCore\App\Block
     */
    protected $block;

    /**
     * Rollback
     *
     * @var boolean
     */
    protected $rollback;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Block $block, $rollback=false)
    {
        $this->block = $block;
        $this->rollback = $rollback;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // One asset per address
        $unique_changes = $this->getUniqueBalanceChanges();

        // Each changed balance
        foreach($unique_changes as $balance)
        {
            // Update balance
            UpdateBalance::dispatch($balance['address'], $balance['asset'], $this->block, $this->rollback);
        }
    }

    /**
     * Get unique balance changes.
     *
     * @return array
     */
    private function getUniqueBalanceChanges()
    {
        // All credits and debits
        $changes = $this->getBalanceChanges();

        // Unique to address-asset
        return $changes->unique(function ($change) {
            return $change['address'].$change['asset'];
        });
    }

    /**
     * Get balance changes.
     *
     * @return array
     */
    private function getBalanceChanges()
    {
        // All credits
        $credits = $this->getCredits();

        // All debits
        $debits = $this->getDebits();

        // Merge into a collection
        return collect(array_merge($credits, $debits));
    }

    /**
     * Get credits in block.
     *
     * @return array
     */
    private function getCredits()
    {
        if($this->rollback)
        {
            $credits = Credit::where('block_index', '>', $this->block->block_index);
        }
        else
        {
            $credits = Credit::where('block_index', '=', $this->block->block_index);
        }

        return $credits->select('address', 'asset')
            ->groupBy('address', 'asset')
            ->get()
            ->toArray();
    }

    /**
     * Get debits in block.
     *
     * @return array
     */
    private function getDebits()
    {
        if($this->rollback)
        {
            $debits = Debit::where('block_index', '>', $this->block->block_index);
        }
        else
        {
            $debits = Debit::where('block_index', '=', $this->block->block_index);
        }

        return $debits->select('address', 'asset')
            ->groupBy('address', 'asset')
            ->get()
            ->toArray();
    }
}