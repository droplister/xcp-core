<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class RpsExpiration extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'rps_index';

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
         'rps_index',
         'rps_hash',
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
