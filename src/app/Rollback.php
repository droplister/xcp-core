<?php

namespace Droplister\XcpCore\App;

use Illuminate\Database\Eloquent\Model;

class Rollback extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'message_index',
        'processed_at',
    ];

    /**
     * The attributes that are dates.
     *
     * @var array
     */
    protected $dates = [
        'processed_at',
    ];
}
