<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class Btcpay extends Model
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
        'order_match_id',
        'status',
        'source',
        'destination',
        'btc_amount',
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
        'btc_amount_normalized',
    ];

    /**
     * BTC Amount Normalized
     *
     * @return string
     */
    public function getBtcAmountNormalizedAttribute()
    {
        return fromSatoshi($this->btc_amount);
    }
}
