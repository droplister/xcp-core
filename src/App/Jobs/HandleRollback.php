<?php

namespace Droplister\XcpCore\App\Jobs;

use DB, Log;
use Carbon\Carbon;
use Droplister\XcpCore\App\Block;
use Droplister\XcpCore\App\Rollback;
use Droplister\XcpCore\App\Jobs\UpdateBalances;
use Illuminate\Support\Facades\Redis;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class HandleRollback implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Handle Rollback Job
    |--------------------------------------------------------------------------
    |
    | The purpose of this job is to perform a rollback, on-demand. Even
    | outside of normal indexing and syncing, which can be useful
    | in reducing the need for full re-syncs while parsing.
    |
    */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Bindings
     *
     * @var array
     */
    protected $bindings;

    /**
     * Message
     *
     * @var array
     */
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return void
     */
    public function __construct($message, $bindings)
    {
        $this->message = $message;
        $this->bindings = $bindings;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Record the rollback
        $rollback = $this->createRollback();

        Log::info('Rollback #'. $rollback->id);

        // Rollback activated
        if($rollback->wasRecentlyCreated)
        {
            // Clear jobs in the queue
            Redis::command('flushdb');

            Log::info('Queue Cleared');

            // UpdateBalances requires Block instance
            $block = Block::find($this->bindings['block_index']);

            Log::info('Block #' . $block->block_index);

            // Rollback balance first
            UpdateBalances::dispatchNow($block, true);

            Log::info('Balances Update');

            // Rollback to block index
            $this->rollbackDatabase($rollback);

            Log::info('Database Fixed');
        }
    }

    /**
     * First or create Rollback.
     *
     * @return \Droplister\XcpCore\App\Rollback
     */
    private function createRollback()
    {
        return Rollback::firstOrCreate([
          'message_index' => $this->message['message_index'],
          'block_index' => $this->bindings['block_index'],
        ]);
    }

    /**
     * Rollback database.
     *
     * @param  \Droplister\XcpCore\App\Rollback  $rollback
     * @return void
     */
    private function rollbackDatabase($rollback)
    {
        DB::transaction(function () use ($rollback)
        {
            // Tables to rollback
            $tables = $this->rollbackTables();

            // Delete rows after reorg block
            foreach($tables as $table)
            {
                DB::table($table)->where('block_index', '>', $this->bindings['block_index'])->delete();
            }

            // Deactivate
            $rollback->update([
                'processed_at' => Carbon::now()
            ]);
        });
    }

    /**
     * Rollback Tables
     * 
     * @return array
     */
    private function rollbackTables()
    {
        return [
            'blocks',
            'messages',
            'transactions',
            'addresses',
            'assets',
            'bet_expirations',
            'bet_match_expirations',
            'bet_match_resolutions',
            'bet_matches',
            'bets',
            'broadcasts',
            'btcpays',
            'burns',
            'cancels',
            'credits',
            'debits',
            'destructions',
            'dispensers',
            'dispenses',
            'dividends',
            'issuances',
            'order_expirations',
            'order_match_expirations',
            'order_matches',
            'orders',
            'replaces',
            'rps',
            'rps_expirations',
            'rps_match_expirations',
            'rpsresolves',
            'sends',
            'sweeps',
        ];
    }
}
