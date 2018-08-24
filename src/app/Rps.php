<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Traits\CreatesWithLock;
use Droplister\XcpCore\App\Events\RpsWasCreated;
use Droplister\XcpCore\App\Events\RpsWasUpdated;

use Illuminate\Database\Eloquent\Model;

class Rps extends Model
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
        'created' => RpsWasCreated::class,
        'updated' => RpsWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'expire_index',
        'tx_index',
        'tx_hash',
        'status',
        'source',
        'wager',
        'possible_moves',
        'move_random_hash',
        'expiration',
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
        'wager_normalized'
    ];

    /**
     * Wager Normalized
     *
     * @return string
     */
    public function getWagerNormalizedAttribute()
    {
        return fromSatoshi($this->wager);
    }
}
