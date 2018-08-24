<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Traits\CreatesWithLock;
use Droplister\XcpCore\App\Events\BetMatchResolutionWasCreated;

use Illuminate\Database\Eloquent\Model;

class BetMatchResolution extends Model
{
    use CreatesWithLock;
    
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
        'created' => BetMatchResolutionWasCreated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'bet_match_id',
        'bet_match_type_id',
        'winner',
        'bear_credit',
        'bull_credit',
        'escrow_less_fee',
        'fee',
        'settled',
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
