<?php

namespace Droplister\XcpCore\App;

use Droplister\XcpCore\App\Events\AddressWasCreated;
use Droplister\XcpCore\App\Events\AddressWasUpdated;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /**
     * Primary Key
     *
     * @var string
     */
    protected $primaryKey = 'address';

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
        'created' => AddressWasCreated::class,
        'updated' => AddressWasUpdated::class,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'block_index',
        'address',
        'type',
        'options',
        'burn',
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

    /**
     * Issued Assets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function issuedAssets()
    {
        return $this->hasMany(Asset::class, 'issuer');
    }

    /**
     * Owned Assets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedAssets()
    {
        return $this->hasMany(Asset::class, 'owner');
    }

    /**
     * Balances
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function balances()
    {
        return $this->hasMany(Balance::class, 'address');
    }

    /**
     * Bets
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bets()
    {
        return $this->hasMany(Bet::class, 'source');
    }

    /**
     * Broadcasts
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function broadcasts()
    {
        return $this->hasMany(Broadcast::class, 'source');
    }

    /**
     * Credits
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function credits()
    {
        return $this->hasMany(Credit::class, 'address');
    }

    /**
     * Debits
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function debits()
    {
        return $this->hasMany(Debit::class, 'address');
    }

    /**
     * Dispensers
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dispensers()
    {
        return $this->hasMany(Dispenser::class, 'source');
    }

    /**
     * Dispenses
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dispenses()
    {
        return $this->hasMany(Dispense::class, 'destination');
    }

    /**
     * Dividends
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dividends()
    {
        return $this->hasMany(Dividend::class, 'source');
    }

    /**
     * Issuances
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function issuances()
    {
        return $this->hasMany(Issuance::class, 'issuer');
    }

    /**
     * Orders
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'source');
    }

    /**
     * Sends
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sends()
    {
        return $this->hasMany(Send::class, 'source');
    }

    /**
     * Swepts
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function swetps()
    {
        return $this->hasMany(Sweep::class, 'source');
    }

    /**
     * Sweeps
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sweeps()
    {
        return $this->hasMany(Sweep::class, 'destination');
    }

    /**
     * Transactions
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'source');
    }

    /**
     * First or Create Address
     *
     * @param  array  $address
     * @param  array  $bindings
     * @return \Droplister\XcpCore\App\Address
     */
    public static function firstOrCreateAddress($address, $bindings)
    {
        return static::firstOrCreate([
            'address' => $address,
        ],[
            'type' => getAddressType($address),
            'options' => 0,
            'block_index' => $bindings['block_index'],
            'confirmed_at' => $bindings['confirmed_at'],
         ]);
    }

    /**
     * Create Addresses
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return \Droplister\XcpCore\App\Address
     */
    public static function createAddresses($message, $bindings)
    {
        // Needles
        $address_columns = [
            'source',
            'issuer',
            'address',
            'destination',
            'tx0_address',
            'tx1_address'
        ];

        // Haystack
        foreach($address_columns as $address_column)
        {
            // Needle in Haystack
            if(isset($bindings[$address_column]))
            {
                // Create Address
                $address = static::firstOrCreateAddress($bindings[$address_column], $bindings);

                // Handle Multisig
                if($address->type === 'multisig')
                {
                    static::createMultisigAddresses($bindings[$address_column], $bindings);
                }
            }
        }
    }

    /**
     * Create Multisig Addresses
     *
     * @param  string  $multisig
     * @param  array  $bindings
     * @return void
     */
    public static function createMultisigAddresses($multisig, $bindings)
    {
        // Explode Multisig
        $addresses = explodeMultisig($multisig);

        // Create Addresses
        foreach($addresses as $address)
        {
            static::firstOrCreateAddress($address, $bindings);
        }
    }

    /**
     * Update Address Options
     *
     * @param  array  $bindings
     * @return \Droplister\XcpCore\App\Address
     */
    public static function updateAddressOptions($bindings)
    {
        return static::updateOrCreate([
            'address' => $bindings['address'],
        ],[
            'options' => $bindings['options'],
        ]);
    }
}