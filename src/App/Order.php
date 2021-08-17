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
    //     'trading_type',
    //     'trading_pair_normalized',
    //     'trading_pair_base_asset',
    //     'trading_pair_quote_asset',
    //     'trading_price_normalized',
    //     'trading_total_normalized',
    //     'trading_quantity_normalized',
    // ];

    /**
     * Get Quantity Normalized
     *
     * @return string
     */
    public function getGetQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('o_geqn_' . $this->tx_index, function () {
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
        if($this->status === 'open')
        {
            return Cache::remember('o_gern_' . $this->tx_index, 1, function () {
                return normalizeQuantity($this->get_remaining, $this->getAssetModel->divisible);
            });
        }

        return Cache::rememberForever('o_gern_' . $this->tx_index, function () {
            return normalizeQuantity($this->get_remaining, $this->getAssetModel->divisible);
        });
    }

    /**
     * Give Quantity Normalized
     *
     * @return string
     */
    public function getGiveQuantityNormalizedAttribute()
    {
        return Cache::rememberForever('o_giqn_' . $this->tx_index, function () {
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
            return Cache::remember('o_girn_' . $this->tx_index, 1, function () {
                return normalizeQuantity($this->give_remaining, $this->giveAssetModel->divisible);
            });
        }

        return Cache::rememberForever('o_girn_' . $this->tx_index, function () {
            return normalizeQuantity($this->give_remaining, $this->giveAssetModel->divisible);
        });
    }

    /**
     * Trading Type
     *
     * @return string
     */
    public function getTradingTypeAttribute()
    {
        return Cache::rememberForever('o_tt_' . $this->tx_index, function () {
            return $this->getAssetModel->display_name === $this->trading_pair_base_asset ? 'Buy' : 'Sell';
        });
    }

    /**
     * Trading Pair Normalized
     *
     * @return string
     */
    public function getTradingPairNormalizedAttribute()
    {
        return Cache::rememberForever('o_tp_' . $this->tx_index, function () {
            $assets = assetsToTradingPair($this->get_asset, $this->give_asset);
            $base_asset = Asset::find($assets[0])->display_name;
            $quote_asset = Asset::find($assets[1])->display_name;

            return "{$base_asset}/{$quote_asset}";
        });
    }

    /**
     * Trading Base Asset
     *
     * @return string
     */
    public function getTradingPairBaseAssetAttribute()
    {
        return Cache::rememberForever('o_tba_' . $this->tx_index, function () {
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
    public function getTradingPairQuoteAssetAttribute()
    {
        return Cache::rememberForever('o_tqa_' . $this->tx_index, function () {
            return Asset::where('asset_name', '=', explode('/', $this->trading_pair_normalized)[1])
                ->orWhere('asset_longname', '=', explode('/', $this->trading_pair_normalized)[1])
                ->first()
                ->display_name;
        });
    }

    /**
     * Trading Price Normalized
     *
     * @return string
     */
    public function getTradingPriceNormalizedAttribute()
    {
        return Cache::rememberForever('o_tpn_' . $this->tx_index, function () {
            $quantities = quantitiesInBaseQuoteOrder($this->get_asset, $this->get_quantity_normalized, $this->give_asset, $this->give_quantity_normalized);

            return quantitiesToTradingPrice($quantities[0], $quantities[1]);
        });
    }

    /**
     * Trading Quantity Normalized
     *
     * @return string
     */
    public function getTradingQuantityNormalizedAttribute()
    {
        if($this->status === 'open')
        {
            return Cache::remember('o_tqn_' . $this->tx_index, 1, function () {
                return $this->trading_pair_base_asset === $this->get_asset ? $this->get_remaining_normalized : $this->give_remaining_normalized;
            });
        }

        return Cache::rememberForever('o_tqn_' . $this->tx_index, function () {
            return $this->trading_pair_base_asset === $this->get_asset ? $this->get_remaining_normalized : $this->give_remaining_normalized;
        });
    }

    /**
     * Trading Total Normalized
     *
     * @return string
     */
    public function getTradingTotalNormalizedAttribute()
    {
        if($this->status === 'open')
        {
            return Cache::remember('o_ttn_' . $this->tx_index, 1, function () {
                return $this->trading_pair_base_asset === $this->get_asset ? $this->give_remaining_normalized : $this->get_remaining_normalized;
            });
        }

        return Cache::rememberForever('o_ttn_' . $this->tx_index, function () {
            return $this->trading_pair_base_asset === $this->get_asset ? $this->give_remaining_normalized : $this->get_remaining_normalized;
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
