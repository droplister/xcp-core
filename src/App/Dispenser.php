<?php

namespace Droplister\XcpCore\App;

use Cache;
use Droplister\XcpCore\App\Events\DispenserWasCreated;
use Droplister\XcpCore\App\Events\DispenserWasUpdated;

use Illuminate\Database\Eloquent\Model;

class Dispenser extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'tx_index';

    /**
     * Incrementing?
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => DispenserWasCreated::class,
        'updated' => DispenserWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tx_index',
        'block_index',
        'tx_hash',
        'source',
        'asset',
        'give_quantity',
        'give_remaining',
        'escrow_quantity',
        'satoshirate',
        'status',
        'confirmed_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'confirmed_at',
    ];

    /**
     * The attributes that are appended.
     *
     * @var array
     */
    // protected $appends = [
    //     'give_quantity_normalized',
    //     'give_remaining_normalized',
    //     'escrow_quantity_normalized',
    //     'trading_price_normalized',
    //     'status_label',
    // ];

    /**
     * Give Quantity Normalized
     *
     * @return string
     */
    public function getGiveQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('d_giqn_' . $this->tx_index, function () {
            return normalizeQuantity($this->give_quantity, $this->giveAssetModel->divisible);
        });
    }

    /**
     * Give Remaining Normalized
     *
     * @return string
     */
    public function getGiveRemainingNormalizedAttribute()
    {
        if($this->status === 'open')
        {
            return Cache::remember('d_girn_' . $this->tx_index, 1, function () {
                return normalizeQuantity($this->give_remaining, $this->giveAssetModel->divisible);
            });
        }

        return Cache::rememberForever('d_girn_' . $this->tx_index, function () {
            return normalizeQuantity($this->give_remaining, $this->giveAssetModel->divisible);
        });
    }

    /**
     * Escrow Quantity Normalized
     *
     * @return string
     */
    public function getEscrowQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('d_eqn_' . $this->tx_index, function () {
            return normalizeQuantity($this->escrow_quantity, $this->giveAssetModel->divisible);
        });
    }

    /**
     * Trading Price Normalized
     *
     * @return string
     */
    public function getTradingPriceNormalizedAttribute()
    {
        return Cache::rememberForever('d_tpn_' . $this->tx_index, function () {
            return normalizeQuantity($this->satoshirate, true);
        });
    }

    /**
     * Status Label
     *
     * @return string
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 0:
                return 'open';
            case 1:
                return 'open (empty)';
            case 10:
                return 'closed';
        }
    }

    /**
     * Give Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function giveAssetModel()
    {
        return $this->belongsTo(Asset::class, 'asset', 'asset_name');
    }
}
