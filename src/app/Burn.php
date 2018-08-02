<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class Burn extends Model
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
        'burned',
        'earned',
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
        'burned_normalized',
        'earned_normalized',
    ];

    /**
     * Burned Normalized
     *
     * @return string
     */
    public function getBurnedNormalizedAttribute()
    {
        return fromSatoshi($this->burned);
    }

    /**
     * Earned Normalized
     *
     * @return string
     */
    public function getEarnedNormalizedAttribute()
    {
        return fromSatoshi($this->earned);
    }
}
