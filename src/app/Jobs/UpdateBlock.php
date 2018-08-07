<?php

namespace Droplister\XcpCore\App\Jobs;

use Throwable;
use JsonRPC\Client;
use Droplister\XcpCore\App\Block;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBlock implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Update Block Job
    |--------------------------------------------------------------------------
    |
    | The purpose of this job is to fetch a block's data from the Bitcoin
    | Core API, so we have richer knowledge of the blockchain. However, 
    | it is not required. Simply don't provide credentials to skip.
    | 
    */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Bitcoin Core API
     *
     * @var \JsonRPC\Client
     */
    protected $bitcoin;

    /**
     * Block
     *
     * @var \Droplister\XcpCore\App\Block
     */
    protected $block;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Block $block)
    {
        $this->bitcoin = new Client(config('xcp-core.bc.api'));
        $this->bitcoin->authentication(config('xcp-core.bc.user'), config('xcp-core.bc.password'));
        $this->block = $block;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try
        {
            // Get block data
            $block_data = $this->getBlockData();

            // Update block
            $this->block->updateBlock($block_data);
        }
        catch(Throwable $e)
        {
            // API Error
        }
    }

    /**
     * Bitcoin Core API
     * https://bitcoin.org/en/developer-reference#getblock
     *
     * @return mixed
     */
    private function getBlockData()
    {
        return $this->bitcoin->execute('getblock', [
            $this->block->block_hash
        ]);
    }
}