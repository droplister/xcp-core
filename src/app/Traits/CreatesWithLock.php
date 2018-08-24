<?php

namespace Droplister\XcpCore\App\Traits;

trait CreatesWithLock
{
    /*
    |--------------------------------------------------------------------------
    | Creates With Lock Trait
    |--------------------------------------------------------------------------
    |
    | Guard against race conditions in Laravel's firstOrCreate and updateOrCreate
    | https://gist.github.com/troatie/def0fba42fcfb70f873b7f033fbe255f
    |
    */

    /**
     * updateOrCreate (advisoryLock)
     * 
     * @param  array  $attributes
     * @param  array  $values
     * @return mixed
     */
    public static function updateOrCreate(array $attributes, array $values = [])
    {
        return static::advisoryLock(function () use ($attributes, $values) {
            // emulate the code found in Illuminate\Database\Eloquent\Builder
            return (new static)->newQuery()->updateOrCreate($attributes, $values);
        });
    }

    /**
     * firstOrCreate (advisoryLock)
     * 
     * @param  array  $attributes
     * @param  array  $values
     * @return mixed
     */
    public static function firstOrCreate(array $attributes, array $values = [])
    {
        return static::advisoryLock(function () use ($attributes, $values) {
            return (new static)->newQuery()->firstOrCreate($attributes, $values);
        });
    }

    /**
     * advisoryLock
     * 
     * @param  callable $callback
     * @return mixed
     */
    private static function advisoryLock(callable $callback)
    {
        // Lock name based on Model.
        $lockName = substr(static::class . ' *OrCreate lock', -64);

        // Lock for at most 10 seconds.  This is the MySQL >5.7.5 implementation.
        // Older MySQL versions have some weird behavior with GET_LOCK().
        // Other databases have a different implementation.
        \DB::statement("SELECT GET_LOCK('" . $lockName . "', 10)");

        $output = $callback();
        \DB::statement("SELECT RELEASE_LOCK('" . $lockName . "')");
        return $output;
    }
}