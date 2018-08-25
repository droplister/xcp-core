<?php

namespace Droplister\XcpCore\App;

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
    protected $appends = [
        'backward_quantity_normalized',
        'forward_quantity_normalized',
    ];

    /**
     * Get Backward Quantity Normalized
     *
     * @return string
     */
    public function getBackwardQuantityNormalizedAttribute()
    {
        return normalizeQuantity($this->backward_quantity, $this->backwardAssetModel->divisible);
    }

    /**
     * Get Forward Quantity Normalized
     *
     * @return string
     */
    public function getForwardQuantityNormalizedAttribute()
    {
        return normalizeQuantity($this->forward_quantity, $this->forwardAssetModel->divisible);
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