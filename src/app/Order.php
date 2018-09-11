<?php

namespace Droplister\XcpCore\App;

use Cache;
use Droplister\XcpCore\App\Asset;
use Droplister\XcpCore\App\Events\OrderWasCreated;
use Droplister\XcpCore\App\Events\OrderWasUpdated;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
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
        'created' => OrderWasCreated::class,
        'updated' => OrderWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'expire_index',
        'tx_index',
        'tx_hash',
        'status',
        'source',
        'expiration',
        'give_asset',
        'give_quantity',
        'give_remaining',
        'get_asset',
        'get_quantity',
        'get_remaining',
        'fee_provided',
        'fee_provided_remaining',
        'fee_required',
        'fee_required_remaining',
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
    //     'get_quantity_normalized',
    //     'get_remaining_normalized',
    //     'give_quantity_normalized',
    //     'give_remaining_normalized',
    //     'trading_pair_normalized',
    //     'trading_price_normalized',
    // ];

    /**
     * Get Quantity Normalized
     *
     * @return string
     */
    public function getGetQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('o_geqn_' . $this->id, function () {
            return normalizeQuantity($this->get_quantity, $this->getAssetModel->divisible);
        });
    }

    /**
     * Get Remaining Normalized
     *
     * @return string
     */
    public function getGetRemainingNormalizedAttribute()
    {
        return normalizeQuantity($this->get_remaining, $this->getAssetModel->divisible);
    }

    /**
     * Give Quantity Normalized
     *
     * @return string
     */
    public function getGiveQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('o_giqn_' . $this->id, function () {
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
        return normalizeQuantity($this->give_remaining, $this->giveAssetModel->divisible);
    }

    /**
     * Trading Pair Normalized
     *
     * @return string
     */
    public function getTradingPairNormalizedAttribute()
    {
        return Cache::rememberForever('o_tp_' . $this->id, function () {
            $assets = assetsToTradingPair($this->get_asset, $this->give_asset);
            $base_asset = Asset::find($assets[0])->display_name;
            $quote_asset = Asset::find($assets[1])->display_name;

            return "{$base_asset}/{$quote_asset}";
        });
    }

    /**
     * Trading Price Normalized
     *
     * @return string
     */
    public function getTradingPriceNormalizedAttribute()
    {
        return Cache::rememberForever('o_tpn_' . $this->id, function () {
            $quantities = quantitiesInBaseQuoteOrder($this->get_asset, $this->get_quantity_normalized, $this->give_asset, $this->give_quantity_normalized);

            return quantitiesToTradingPrice($quantities[0], $quantities[1]);
        });
    }

    /**
     * Get Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getAssetModel()
    {
        return $this->belongsTo(Asset::class, 'get_asset', 'asset_name');
    }

    /**
     * Give Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function giveAssetModel()
    {
        return $this->belongsTo(Asset::class, 'give_asset', 'asset_name');
    }
}
