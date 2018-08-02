<?php

namespace Droplister\XcpCore\App\Jobs;

use JsonRPC\Client;
use Droplister\XcpCore\App\Mempool;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateMempool implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Update Mempool Job
    |--------------------------------------------------------------------------
    |
    | The purpose of this job is to fetch the transactions which are in the
    | mempool and save them to their own dedicated table, separate from
    | actually confirmed transactions. Can be useful for monitoring. 
    | 
    */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Counterparty API
     *
     * @var \JsonRPC\Client
     */
    protected $counterparty;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->counterparty = new Client(config('xcp-core.cp.api'));
        $this->counterparty->authentication(config('xcp-core.cp.user'), config('xcp-core.cp.password'));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get mempool
        $mempool = $this->getMempool();

        // Returned as messages
        foreach($mempool as $message)
        {
            // Limit to inserts
            if($message['command'] === 'insert')
            {
                // Save mempool tx
                Mempool::firstOrCreate($message);
            }
        }
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_table
     *
     * @return mixed
     */
    private function getMempool()
    {
        return $this->counterparty->execute('get_mempool');
    }
}