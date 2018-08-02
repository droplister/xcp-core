<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class OrderExpiration extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'order_index';

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
         'order_index',
         'order_hash',
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
