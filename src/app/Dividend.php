<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class Dividend extends Model
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'tx_index',
        'tx_hash',
        'status',
        'source',
        'asset',
        'dividend_asset',
        'quantity_per_unit',
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
    protected $appends = [
        'fee_paid_normalized',
        'quantity_per_unit_normalized',
    ];

    /**
     * Fee Paid Normalized
     *
     * @return string
     */
    public function getFeePaidNormalizedAttribute()
    {
        return fromSatoshi($this->fee_paid);
    }

    /**
     * Quantity Per Unit Normalized
     *
     * @return string
     */
    public function getQuantityPerUnitNormalizedAttribute()
    {
        return normalizeQuantity($this->quantity_per_unit, $this->dividendAssetModel->divisible);
    }

    /**
     * Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assetModel()
    {
        return $this->belongsTo(Asset::class, 'asset', 'asset_name');
    }

    /**
     * Dividend Asset
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dividendAssetModel()
    {
        return $this->belongsTo(Asset::class, 'dividend_asset', 'asset_name');
    }
}
