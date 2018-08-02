<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class Debit extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'event',
        'action',
        'address',
        'asset',
        'quantity',
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
        if($this->asset === null) return $this->quantity; // Edge Case

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

    /**
     * Transaction
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'event', 'tx_hash');
    }
}
