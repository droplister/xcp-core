<?php

namespace Droplister\XcpCore\App;

use Cache;
use Droplister\XcpCore\App\Events\SweepWasCreated;

use Illuminate\Database\Eloquent\Model;

class Sweep extends Model
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
        'created' => SweepWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tx_index',
        'block_index',
        'tx_hash',
        'source',
        'destination',
        'flags',
        'status',
        'memo',
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
    // protected $appends = [
    //     'flags_label',
    // ];

    /**
     * Flags Label
     *
     * @return string
     */
    public function getFlagsLabelAttribute()
    {
        switch ($this->flags) {
            case 1:
                return 'balances';
            case 2:
                return 'ownership';
            case 4:
                return 'binary_memo';
            default:
                return 'all';
        }
    }
}
