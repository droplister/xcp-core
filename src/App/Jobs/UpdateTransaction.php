<?php

namespace Droplister\XcpCore\App\Jobs;

use Exception;
use JsonRPC\Client;
use Droplister\XcpCore\App\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateTransaction implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Update Transaction Job
    |--------------------------------------------------------------------------
    |
    | The purpose of this job is to fetch additional data that the get_blocks
    | command on the Counterparty API does not provide, like the fee, size,
    | amount of BTC sent, inputs, outputs, and the raw transaction data.
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
     * Transaction
     *
     * @var \Droplister\XcpCore\App\Transaction
     */    
    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->counterparty = new Client(config('xcp-core.cp.api'));
        $this->counterparty->authentication(config('xcp-core.cp.user'), config('xcp-core.cp.password'));
        $this->transaction = $transaction;
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
            // Skip if already processed
            if(! $this->transaction->processed_at)
            {
                // Get raw tx
                $raw = $this->getRawTransaction();

                // Get tx info
                $data = $this->getTxInfo($raw);

                // Requires both
                if($raw && $data)
                {
                    // Update transaction
                    $this->transaction->updateTransaction($raw, $data);
                }
            }
        }
        catch(Exception $e)
        {
            // API Error
        }
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#getrawtransaction
     *
     * @return mixed
     */
    private function getRawTransaction()
    {
        return $this->counterparty->execute('getrawtransaction', [
            'tx_hash' => $this->transaction->tx_hash,
            'verbose' => true,
        ]);
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_tx_info
     *
     * @return mixed
     */
    private function getTxInfo($raw)
    {
        return $this->counterparty->execute('get_tx_info', [
            'tx_hex' => $raw['hex'],
            'block_index' => $this->transaction->block_index,
        ]);
    }
}
