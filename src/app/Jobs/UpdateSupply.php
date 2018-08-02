<?php

namespace Droplister\XcpCore\App\Jobs;

use JsonRPC\Client;
use Droplister\XcpCore\App\Asset;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateSupply implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Update Supply Job
    |--------------------------------------------------------------------------
    |
    | The purpose of this job is to fetch the current supply a.k.a. issuance
    | of assets, like BTC and XCP, and update our records to reflect that
    | data because BTC is always growing and XCP is always shrinking.
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
     * Asset
     *
     * @var \Droplister\XcpCore\App\Asset
     */
    protected $asset;

    /**
     * Create a new job instance.
     *
     * @param  \Droplister\XcpCore\App\Asset  $asset
     * @return void
     */
    public function __construct(Asset $asset)
    {
        $this->counterparty = new Client(config('xcp-core.cp.api'));
        $this->counterparty->authentication(config('xcp-core.cp.user'), config('xcp-core.cp.password'));
        $this->asset = $asset;
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
            // Get supply
            $supply = $this->getSupply();

            // Update issuance
            $this->asset->update(['issuance' => $supply]);
        }
        catch(\Exception $e)
        {
            // API Error
        }
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_supply
     *
     * @return integer
     */
    private function getSupply()
    {
        return $this->counterparty->execute('get_supply', [
            'asset' => $this->asset->asset_name
        ]);
    }
}