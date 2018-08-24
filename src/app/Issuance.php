<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Traits\CreatesWithLock;
use Droplister\XcpCore\App\Events\IssuanceWasCreated;

use Illuminate\Database\Eloquent\Model;

class Issuance extends Model
{
    use CreatesWithLock;
    
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
        'created' => IssuanceWasCreated::class,
    ];

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
        'issuer',
        'asset',
        'asset_longname',
        'description',
        'quantity',
        'callable',
        'call_date',
        'call_price',
        'divisible',
        'locked',
        'transfer',
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
        'display_name',
        'fee_paid_normalized',
        'quantity_normalized',
    ];

    /**
     * Display Name
     *
     * @return string
     */
    public function getDisplayNameAttribute()
    {
        return $this->asset_longname ? $this->asset_longname : $this->asset;
    }

    /**
     * Fee Paid
     *
     * @return string
     */
    public function getFeePaidNormalizedAttribute()
    {
        return fromSatoshi($this->fee_paid);
    }

    /**
     * Quantity Normalized
     *
     * @return string
     */
    public function getQuantityNormalizedAttribute()
    {
        return normalizeQuantity($this->quantity, $this->assetModel->divisible);
    }

    /**
     * Asset Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assetModel()
    {
        return $this->belongsTo(Asset::class, 'asset', 'asset_name');
    }
}