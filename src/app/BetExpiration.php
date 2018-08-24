<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Traits\CreatesWithLock;
use Droplister\XcpCore\App\Events\BetExpirationWasCreated;

use Illuminate\Database\Eloquent\Model;

class BetExpiration extends Model
{
    use CreatesWithLock;

    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'bet_index';

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
        'created' => BetExpirationWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'block_index',
         'bet_index',
         'bet_hash',
         'source',
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
     * Bet
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bet()
    {
        return $this->belongsTo(Bet::class, 'bet_index', 'tx_index');
    }
}
