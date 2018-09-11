<?php

namespace Droplister\XcpCore\App;

use Cache;
use Droplister\XcpCore\App\Events\OrderMatchWasCreated;
use Droplister\XcpCore\App\Events\OrderMatchWasUpdated;

use Illuminate\Database\Eloquent\Model;

class OrderMatch extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'id';

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
        'created' => OrderMatchWasCreated::class,
        'updated' => OrderMatchWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'match_expire_index',
        'tx0_block_index',
        'tx0_index',
        'tx0_hash',
        'tx0_address',
        'tx0_expiration',
        'tx1_block_index',
        'tx1_index',
        'tx1_hash',
        'tx1_address',
        'tx1_expiration',
        'id',
        'status',
        'backward_asset',
        'backward_quantity',
        'forward_asset',
        'forward_quantity',
        'fee_paid',
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
    //     'backward_quantity_normalized',
    //     'forward_quantity_normalized',
    //     'trading_pair_normalized',
    //     'trading_pair_base_asset',
    //     'trading_pair_quote_asset',
    //     'trading_price_normalized',
    //     'trading_total_normalized',
    //     'trading_quantity_normalized',
    //     'trading_buyer_normalized',
    //     'trading_seller_normalized',
    // ];

    /**
     * Get Backward Quantity Normalized
     *
     * @return string
     */
    public function getBackwardQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('om_bqn_' . $this->id, function () {
            return normalizeQuantity($this->backward_quantity, $this->backwardAssetModel->divisible);
        });
    }

    /**
     * Get Forward Quantity Normalized
     *
     * @return string
     */
    public function getForwardQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('om_fqn_' . $this->id, function () {
            return normalizeQuantity($this->forward_quantity, $this->forwardAssetModel->divisible);
        });
    }

    /**
     * Trading Base Asset
     *
     * @return string
     */
    public function getTradingBaseAssetAttribute()
    {
        return Cache::rememberForever('om_tba_' . $this->id, function () {
            return Asset::where('asset_name', '=', explode('/', $this->trading_pair_normalized)[0])
                ->orWhere('asset_longname', '=', explode('/', $this->trading_pair_normalized)[0])
                ->first()
                ->display_name;
        });
    }

    /**
     * Trading Quote Asset
     *
     * @return string
     */
    public function getTradingQuoteAssetAttribute()
    {
        return Cache::rememberForever('om_tqa_' . $this->id, function () {
            return Asset::where('asset_name', '=', explode('/', $this->trading_pair_normalized)[1])
                ->orWhere('asset_longname', '=', explode('/', $this->trading_pair_normalized)[1])
                ->first()
                ->display_name;
        });
    }

    /**
     * Trading Pair Normalized
     *
     * @return string
     */
    public function getTradingPairNormalizedAttribute()
    {
        return Cache::rememberForever('om_tp_' . $this->id, function () {
            $assets = assetsToTradingPair($this->backward_asset, $this->forward_asset);
            $base_asset = Asset::find($assets[0])->display_name;
            $quote_asset = Asset::find($assets[1])->display_name;

            return "{$base_asset}/{$quote_asset}";
        });
    }

    /**
     * Trading Quantity Normalized
     *
     * @return string
     */
    public function getTradingQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('om_tqn_' . $this->id, function () {
            return $this->base_asset === $this->backward_asset ? $this->backward_quantity_normalized : $this->forward_quantity_normalized;
        });
    }

    /**
     * Trading Total Normalized
     *
     * @return string
     */
    public function getTradingTotalNormalizedAttribute()
    {
        return Cache::rememberForever('om_ttn_' . $this->id, function () {
            return $this->base_asset === $this->backward_asset ? $this->forward_quantity_normalized : $this->backward_quantity_normalized;
        });
    }

    /**
     * Trading Price Normalized
     *
     * @return string
     */
    public function getTradingPriceNormalizedAttribute()
    {
        return Cache::rememberForever('om_tpn_' . $this->id, function () {
            $quantities = quantitiesInBaseQuoteOrder($this->backward_asset, $this->backward_quantity_normalized, $this->forward_asset, $this->forward_quantity_normalized);

            return quantitiesToTradingPrice($quantities[0], $quantities[1]);
        });
    }

    /**
     * Trading Buyer
     *
     * @return string
     */
    public function getTradingBuyerNormalizedAttribute()
    {
        return Cache::rememberForever('om_tb_' . $this->id, function () {
            return $this->base_asset === $this->backward_asset ? $this->tx0_address : $this->tx1_address;
        });
    }

    /**
     * Trading Seller
     *
     * @return string
     */
    public function getTradingSellerNormalizedAttribute()
    {
        return Cache::rememberForever('om_ts_' . $this->id, function () {
            return $this->base_asset === $this->backward_asset ? $this->tx1_address : $this->tx0_address;
        });
    }

    /**
     * Backward Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function backwardAssetModel()
    {
        return $this->belongsTo(Asset::class, 'backward_asset', 'asset_name');
    }

    /**
     * Forward Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forwardAssetModel()
    {
        return $this->belongsTo(Asset::class, 'forward_asset', 'asset_name');
    }
}