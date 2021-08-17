<?php

namespace Droplister\XcpCore\App\Console\Commands;

use Cache;
use Droplister\XcpCore\App\Block;
use Droplister\XcpCore\App\Rollback;
use Droplister\XcpCore\App\Jobs\UpdateBlock;
use Droplister\XcpCore\App\Jobs\UpdateBlocks;
use Illuminate\Console\Command;

class UpdateBlocksCommand extends Command
{
    /*
    |--------------------------------------------------------------------------
    | Update Blocks Command
    |--------------------------------------------------------------------------
    |
    | The purpose of this command is to determine how many blocks behind
    | our database is compared to the blockchain and based on that
    | dispatch an appropriate chunk of blocks for processing.
    |
    */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:blocks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Blocks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Avoid rollbacks
        if($this->guardAgainstSyncingDuringActiveRollbacks())
        {
            // New blocks
            $this->selectAndUpdateNewBlocks();

            // Old blocks
            $this->selectAndUpdateOldBlocks();
        }
    }

    /**
     * Select and update new blocks.
     *
     * @return void
     */
    private function selectAndUpdateNewBlocks()
    {
        // Local block index
        $local_index = $this->localBlockIndex();

        // Global block index
        $global_index = Cache::get('block_index');

        // Update accordingly
        $this->updateNewBlocks($local_index, $global_index);
    }

    /**
     * Update new blocks.
     *
     * @return integer
     */
    private function updateNewBlocks($local_index, $global_index)
    {
        // First/Next block index
        $first_index = $local_index + 1;

        // Minimum "gap" to trigger sync
        $minimum_sync = config('xcp-core.sync_size');

        // Update blocks
        if($global_index - $local_index <= $minimum_sync)
        {
            // Regular update
            $last_index = $global_index;

            UpdateBlocks::dispatch($first_index, $last_index);
        }
        else
        {
            // Syncing update
            $last_index = $first_index + $minimum_sync;

            UpdateBlocks::dispatch($first_index, $last_index, true);
        }
    }

    /**
     * Update old blocks.
     *
     * @return integer
     */
    private function selectAndUpdateOldBlocks()
    {
        // Requires Bitcoin Core API
        if(config('xcp-core.advanced') && config('xcp-core.bc.api'))
        {
            // Get blocks w/o hashes
            $blocks = Block::whereNull('next_block_hash')->get();

            // Update blocks
            foreach($blocks as $block)
            {
                // Extra data (non-XCP)
                UpdateBlock::dispatch($block);
            }
        }
    }

    /**
     * Local block index.
     *
     * @return integer
     */
    private function localBlockIndex()
    {
        // Get block (if exists)
        $block = Block::orderBy('block_index', 'desc')->first();

        return $block ? $block->block_index : config('xcp-core.first_block'); // Launch
    }

    /**
     * Guard against active rollbacks.
     *
     * @return boolean
     */
    private function guardAgainstSyncingDuringActiveRollbacks()
    {
        return Rollback::whereNull('processed_at')->doesntExist();
    }
}