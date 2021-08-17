<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\BetMatchExpirationWasCreated;

use Illuminate\Database\Eloquent\Model;

class BetMatchExpiration extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'bet_match_id';

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
        'created' => BetMatchExpirationWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'block_index',
         'bet_match_id',
         'tx0_address',
         'tx1_address',
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
