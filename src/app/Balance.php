<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\BalanceWasCreated;
use Droplister\XcpCore\App\Events\BalanceWasUpdated;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => BalanceWasCreated::class,
        'updated' => BalanceWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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
        return normalizeQuantity($this->quantity, $this->assetModel->divisible);
    }

    /**
     * Address Model
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function addressModel()
    {
        return $this->belongsTo(Address::class, 'address', 'address');
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
     * Non-Zero Balances
     */
    public function scopeNonZero($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Now Zero Balances
     */
    public function scopeNowZero($query)
    {
        return $query->where('quantity', '=', 0);
    }
}