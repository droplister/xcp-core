<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class BetMatchResolution extends Model
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
