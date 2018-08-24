<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Traits\CreatesWithLock;
use Droplister\XcpCore\App\Events\SendWasCreated;

use Illuminate\Database\Eloquent\Model;

class Send extends Model
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
        'created' => SendWasCreated::class,
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
        'destination',
        'asset',
        'quantity',
        'memo',
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
        'quantity_normalized',
    ];

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
     * Destination Address
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function destinationAddress()
    {
        return $this->belongsTo(Address::class, 'destination', 'address');
    }

    /**
     * Source Address
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sourceAddress()
    {
        return $this->belongsTo(Address::class, 'source', 'address');
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
     * Transaction
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'tx_hash', 'tx_hash');
    }
}
