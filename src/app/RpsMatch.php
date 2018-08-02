<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class RpsMatch extends Model
{
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
        'tx0_address',
        'tx0_expiration',
        'tx0_move_random_hash',
        'tx1_block_index',
        'tx1_index',
        'tx1_hash',
        'tx1_address',
        'tx1_expiration',
        'tx1_move_random_hash',
        'id',
        'status',
        'wager',
        'possible_moves',
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
