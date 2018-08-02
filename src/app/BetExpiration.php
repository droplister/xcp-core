<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class BetExpiration extends Model
{
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
}
