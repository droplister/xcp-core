<?php

namespace Droplister\XcpCore\App\Console\Commands;

use Droplister\XcpCore\App\Asset;
use Droplister\XcpCore\App\Jobs\UpdateSupply;
use Illuminate\Console\Command;

class UpdateSupplyCommand extends Command
{
    /*
    |--------------------------------------------------------------------------
    | Update Supply Command
    |--------------------------------------------------------------------------
    |
    | The purpose of this command is to fetch the current supply or issuance
    | of assets, like BTC and XCP, and update our records to reflect that
    | data because BTC is always growing and XCP is always shrinking.
    |
    */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:supply';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update BTC/XCP Supply';

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
        // BTC + XCP
        $assets = Asset::whereIn('asset_name', ['BTC', 'XCP'])->get();

        // Each asset
        foreach($assets as $asset)
        {
            // Update supply
            UpdateSupply::dispatch($asset);
        }
    }
}