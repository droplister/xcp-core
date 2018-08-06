<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
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
        'bet_type',
        'status',
        'source',
        'feed_address',
        'target_value',
        'wager_quantity',
        'wager_remaining',
        'counterwager_quantity',
        'counterwager_remaining',
        'leverage',
        'deadline',
        'expiration',
        'expire_index',
        'fee_fraction_int',
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
        'display_type',
        'wager_quantity_normalized',
        'wager_remaining_normalized',
        'counterwager_quantity_normalized',
        'counterwager_remaining_normalized',
    ];

    /**
     * Display Type
     *
     * @return string
     */
    public function getDisplayTypeAttribute()
    {
        return getBetType($this->type);
    }

    /**
     * Wager Quantity Normalized
     *
     * @return string
     */
    public function getWagerQuantityNormalizedAttribute()
    {
        return fromSatoshi($this->wager_quantity);
    }

    /**
     * Wager Remaining Normalized
     *
     * @return string
     */
    public function getWagerRemainingNormalizedAttribute()
    {
        return fromSatoshi($this->wager_remaining);
    }

    /**
     * Counterwager Quantity Normalized
     *
     * @return string
     */
    public function getCounterwagerQuantityNormalizedAttribute()
    {
        return fromSatoshi($this->counterwager_quantity);
    }

    /**
     * Counterwager Remaining Normalized
     *
     * @return string
     */
    public function getCounterwagerRemainingNormalizedAttribute()
    {
        return fromSatoshi($this->counterwager_remaining);
    }

    /**
     * Bet Expiration
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function expiration()
    {
        return $this->hasOne(BetExpiration::class, 'tx_index', 'bet_index');
    }
}
