<?php

namespace Droplister\XcpCore\Database\Seeds;

use JsonRPC\Client;
use Droplister\XcpCore\App\Asset;
use Illuminate\Database\Seeder;

class AssetsTableSeeder extends Seeder
{
    /**
     * Counterparty API
     *
     * @var \JsonRPC\Client
     */
    protected $counterparty;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->counterparty = new Client(config('xcp-core.cp.api'));
        $this->counterparty->authentication(config('xcp-core.cp.user'), config('xcp-core.cp.password'));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Exceptions to the rule
        $assets = [[
            'asset' => 'BTC',
            'block_index' => 0,
            'confirmed_at' => '2009-01-03 18:15:05',
        ], [
            'asset' => 'XCP',
            'block_index' => 278319,
            'confirmed_at' => '2014-01-02 17:19:37',
        ]];

        // Each asset
        foreach($assets as $asset)
        {
            // Get supply
            $issuance = $this->getSupply($asset);

            // Create it!
            Asset::create([
                'type' => 'asset',
                'divisible' => 1,
                'issuance' => $issuance,
                'asset_name' => $asset['asset'],
                'block_index' => $asset['block_index'],
                'confirmed_at' => $asset['confirmed_at'],
            ]);
        }
    }

    /**
     * Get Supply
     * 
     * @param  array  $asset
     * @return integer
     */
    private function getSupply($asset)
    {
        return $this->counterparty->execute('get_supply', [
            'asset' => $asset['asset'],
        ]);
    }
}
