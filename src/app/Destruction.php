<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class Destruction extends Model
{
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
        'tag',
        'source',
        'asset',
        'quantity',
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
