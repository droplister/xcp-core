<?php

/**
 * From Satoshi (divide)
 * 
 * @param  integer  $satoshi
 * @return string
 */
function fromSatoshi($satoshi)
{
    return bcdiv((int)(string)$satoshi, 100000000, 8);
}

/**
 * To Satoshi (multiply)
 * 
 * @param  float  $decimal
 * @return string
 */
function toSatoshi($decimal)
{
    return bcmul(sprintf("%.8f", (float)$decimal), 100000000, 0);
}

/**
 * Normalize Quantity
 * 
 * @param  integer  $quantity
 * @param  boolean  $divisible
 * @return string
 */
function normalizeQuantity($quantity, $divisible)
{
    if($quantity < 0) $quantity = 0;

    return $divisible ? fromSatoshi($quantity) : sprintf("%.8f", $quantity);
}

/**
 * Get Address Type
 * 
 * @param  string  $address
 * @return string
 */
function getAddressType($address)
{
    if(strpos($address, '_') !== false)
    {
        return 'multisig';
    }
    elseif($address[0] === '1' || $address[0] === 'm' || $address[0] === 'n')
    {
        return 'p2pkh';
    }
    elseif($address[0] === '3' || $address[0] === '2')
    {
        return 'p2sh';
    }
    elseif($address[0] === 'b' || $address[0] === 't')
    {
        return 'bech32';
    }

    return null;
}

/**
 * Explode Multisig
 * 
 * @param  string  $multisig
 * @return array
 */
function explodeMultisig($multisig)
{
    // 1_address_2_address
    $addresses = explode('_', $multisig);

    // ['1', 'address', '2', 'address']
    $addresses = array_filter($addresses, 'lengthFilter');

    // [1 => 'address', 3 => 'address']
    $addresses = array_values($addresses);

    // ['address', 'address']
    return $addresses;
}

/**
 * Length Filter Array Values
 * 
 * @param  string  $value
 * @return boolean
 */
function lengthFilter($value)
{
    return strlen($value) > 1;
}

/**
 * Get Asset Type
 * 
 * @param  \Droplister\XcpCore\App\Issuance  $issuance
 * @return boolean
 */
function getAssetType($issuance)
{
    if($issuance->asset_longname)
    {
        return 'subasset';
    }
    elseif($issuance->asset[0] === 'A')
    {
        return 'numeric';
    }

    return 'asset';
}

/**
 * Get Bet Type
 * 
 * @param  integer  $type
 * @return string
 */
function getBetType($type)
{
    switch($type)
    {
        case 0:
            return 'Bullish CFD';
        case 1:
            return 'Bearish CFD';
        case 2:
            return 'Equal';
        case 3:
            return 'Not Equal';
    }
}

/**
 * Get model name from string
 * 
 * @param  string  $string
 * @return string
 */
function getModelName($string)
{
    // "Edge Cases"
    if($string === 'rps') return '\\Droplister\\XcpCore\\App\\Rps';
    if($string === 'rpsresolves') return '\\Droplister\\XcpCore\\App\\Rpsresolve';

    // order_matches -> order_match
    $model = str_singular($string);

    // order_match -> orderMatch
    $model = camel_case($model);

    // orderMatch -> OrderMatch
    $model = ucfirst($model);

    // Droplister\XcpCore\App\OrderMatch
    return '\\Droplister\\XcpCore\\App\\' . $model;
}

/**
 * Get Lookup Array (firstOrCreate)
 * Attempt to standardize creation of entries.
 * 
 * @param  array  $message
 * @param  array  $bindings
 * @return array
 */
function getLookupArrayFoC($message, $bindings)
{
    // Symmetric Keys
    if(in_array($message['category'], [
        'bets',
        'broadcasts',
        'btcpays',
        'burns',
        'cancels',
        'destructions',
        'dispensers',
        'dispenses',
        'dividends',
        'issuances',
        'orders',
        'rps',
        'rpsresolves',
        'sends'
    ]))
    {
        $model_key = $bindings_key = 'tx_index';
    }
    elseif(in_array($message['category'], [
        'order_matches',
        'bet_matches',
        'rps_matches',
    ]))
    {
        $model_key = $bindings_key = 'id';
    }
    elseif(in_array($message['category'], [
        'order_expirations',
        'bet_expirations',
        'rps_expirations',
    ]))
    {
        $model_key = $bindings_key = generateLookupKey($message['category'], 'index');
    }
    elseif(in_array($message['category'], [
        'order_match_expirations',
        'bet_match_expirations',
        'rps_match_expirations',
        'bet_match_resolutions',
    ]))
    {
        $model_key = $bindings_key = generateLookupKey($message['category'], 'id');
    }
    else
    {
        return false; // credits, debits, replace
    }

    // Lookup: [key => value]
    return [
        $model_key => $bindings[$bindings_key]
    ];
}

/**
 * Get Lookup Array (updateOrCreate)
 * Attempt to standardize updating of entries.
 * 
 * @param  array  $message
 * @param  array  $bindings
 * @return array
 */
function getLookupArrayUoC($message, $bindings)
{
    // Symmetric Keys
    if(in_array($message['category'], ['bets', 'dispensers', 'orders']))
    {
        $model_key = $bindings_key = 'tx_hash';
    }
    elseif($message['category'] === 'rps')
    {
        $model_key = $bindings_key = 'tx_index';
    }
    else
    {
        $model_key = $bindings_key = generateLookupKey($message['category'], 'match_id');
    }

    // Divergent Keys
    if(in_array($message['category'], [
        'order_matches',
        'bet_matches',
        'rps_matches',
    ]))
    {
        $model_key = 'id';
    }

    // Edge Case Keys
    if($message['category'] === 'rps' && ! isset($bindings[$bindings_key]))
    {
        // RPS seems to use tx_index OR tx_hash
        $model_key = $bindings_key = 'tx_hash';
    }

    // Lookup: [key => value]
    return [
        $model_key => $bindings[$bindings_key]
    ];
}

/**
 * Generate key.
 * 
 * @param  string  $haystack
 * @param  string  $replace
 * @return string
 */
function generateLookupKey($haystack, $replace)
{
    // bet_matches -> matches
    $needle = last(explode('_', $haystack));

    // bet_matches -> bet_index
    return str_replace($needle, $replace, $haystack);
}

/**
 * Quantity In Base Quote Order
 * 
 * @return array
 */
function quantitiesInBaseQuoteOrder($get_asset, $get_quantity, $give_asset, $give_quantity)
{
    $assets = assetsToTradingPair($get_asset, $give_asset);

    return $assets[0] === $get_asset ? [$get_quantity, $give_quantity] : [$give_quantity, $get_quantity];
}

/**
 * Quantities to Trading Price
 * 
 * @param  float  $base_quantity
 * @param  float  $quote_quantity
 * @return float
 */
function quantitiesToTradingPrice($base_quantity, $quote_quantity)
{
    $price = $quote_quantity / $base_quantity;

    return sprintf("%.8f", $price);
}

/**
 * Assets to Trading Pair
 * 
 * @param  string  $asset1
 * @param  string  $asset2
 * @return string
 */
function assetsToTradingPair($asset1, $asset2)
{
    // Get Quote Assets
    $quote_assets = config('xcp-core.quote_assets');

    // Standardize Pair
    foreach($quote_assets as $quote_asset)
    {
        // Default Quote Asset
        if($asset1 === $quote_asset || $asset2 === $quote_asset)
        {
            return $asset1 === $quote_asset ? [$asset2, $asset1] : [$asset1, $asset2];
        }
    }

    // Default to Alphabetical
    return $asset1 < $asset2 ? [$asset1, $asset2] : [$asset2, $asset1];
}