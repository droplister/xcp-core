<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Traits\CreatesWithLock;
use Droplister\XcpCore\App\Events\BetMatchWasCreated;
use Droplister\XcpCore\App\Events\BetMatchWasUpdated;

use Illuminate\Database\Eloquent\Model;

class BetMatch extends Model
{
    use CreatesWithLock;

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
        'created' => BetMatchWasCreated::class,
        'updated' => BetMatchWasUpdated::class,
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
        'tx0_bet_type',
        'tx0_address',
        'tx0_expiration',
        'tx1_block_index',
        'tx1_index',
        'tx1_hash',
        'tx1_bet_type',
        'tx1_address',
        'tx1_expiration',
        'id',
        'status',
        'feed_address',
        'target_value',
        'initial_value',
        'backward_quantity',
        'forward_quantity',
        'leverage',
        'deadline',
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
}
