<?php

namespace Droplister\XcpCore\App\Jobs;

use Droplister\XcpCore\App\Block;
use Droplister\XcpCore\App\Debit;
use Droplister\XcpCore\App\Credit;
use Droplister\XcpCore\App\Balance;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBalance implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Update Balance Job
    |--------------------------------------------------------------------------
    |
    | The purpose of this job is to take a given address and asset and then
    | determine what the balance of that account was at a given block by
    | subtracting the sum of all debits from the sum of all credits.
    |
    */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Address
     *
     * @var string
     */
    protected $address;

    /**
     * Asset
     *
     * @var string
     */
    protected $asset;

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
    public function __construct($address, $asset, Block $block, $rollback=false)
    {
        $this->address = $address;
        $this->asset = $asset;
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
        // Calculate quantity
        $quantity = $this->getQuantity();

        // Use it based on state of application
        if($this->rollback)
        {
            // Rollback a balance
            $this->rollbackBalance($quantity);
        }
        else
        {
            // Update or create balance
            $this->updateBalance($quantity);
        }
    }

    /**
     * Get quantity.
     *
     * @return integer
     */
    private function getQuantity()
    {
        return $this->sumOfCredits() - $this->sumOfDebits();
    }

    /**
     * Get sum of credits.
     *
     * @return integer
     */
    private function sumOfCredits()
    {
        if($this->rollback)
        {
            $credits = Credit::where('block_index', '>', $this->block->block_index);
        }
        else
        {
            $credits = Credit::where('block_index', '<=', $this->block->block_index);
        }

        return $credits->where('address', '=', $this->address)
            ->where('asset', '=', $this->asset)
            ->sum('quantity');
    }

    /**
     * Get sum of debits.
     *
     * @return integer
     */
    private function sumOfDebits()
    {
        if($this->rollback)
        {
            $debits = Debit::where('block_index', '>', $this->block->block_index);
        }
        else
        {
            $debits = Debit::where('block_index', '<=', $this->block->block_index);
        }

        return $debits->where('address', '=', $this->address)
            ->where('asset', '=', $this->asset)
            ->sum('quantity');
    }

    /**
     * Update balance.
     *
     * @return integer
     */
    private function updateBalance($quantity)
    {
        return Balance::updateOrCreate([
            'address' => $this->address,
            'asset' => $this->asset,
        ],[
            'quantity' => $quantity >= 0 ? $quantity : 0, // Sanity
            'confirmed_at' => $this->block->confirmed_at,
        ]);
    }

    /**
     * Rollback balance.
     *
     * @return integer
     */
    private function rollbackBalance($rollback)
    {
        $balance = Balance::where('address', '=', $this->address)
            ->where('asset', '=', $this->asset)
            ->first();

        $balance->update([
            'quantity' => $balance->quantity - $rollback
        ]);
    }
}