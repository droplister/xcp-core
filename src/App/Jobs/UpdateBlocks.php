<?php

namespace Droplister\XcpCore\App\Jobs;

use Carbon\Carbon;
use JsonRPC\Client;
use Artisan, Log, Exception;
use Droplister\XcpCore\App\Block;
use Droplister\XcpCore\App\Address;
use Droplister\XcpCore\App\Message;
use Droplister\XcpCore\App\Transaction;
use Droplister\XcpCore\App\Jobs\UpdateBlock;
use Droplister\XcpCore\App\Jobs\UpdateBalances;
use Droplister\XcpCore\App\Jobs\HandleRollback;
use Droplister\XcpCore\App\Jobs\UpdateTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateBlocks implements ShouldQueue
{
    /*
    |--------------------------------------------------------------------------
    | Update Blocks Job
    |--------------------------------------------------------------------------
    |
    | The purpose of this job is to fetch 1-250 blocks from the Counterparty
    | API along with the messages associated with those blocks and from
    | that data replicate the state of Counterparty in our database. 
    |
    */

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Counterparty API
     *
     * @var \JsonRPC\Client
     */
    protected $counterparty;

    /**
     * First Block
     *
     * @var integer
     */
    protected $first_index;

    /**
     * Last Block
     *
     * @var integer
     */
    protected $last_index;

    /**
     * Is Syncing?
     *
     * @var boolean
     */
    protected $syncing;

    /**
     * Create a new job instance.
     *
     * @param  integer  $first_index
     * @param  integer  $last_index
     * @param  boolean  $syncing
     * @return void
     */
    public function __construct($first_index, $last_index, $syncing=false)
    {
        $this->counterparty = new Client(config('xcp-core.cp.api'));
        $this->counterparty->authentication(config('xcp-core.cp.user'), config('xcp-core.cp.password'));
        $this->first_index = $first_index;
        $this->last_index = $last_index;
        $this->syncing = $syncing;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try
        {
            // Get blocks
            $blocks = $this->getBlocks();

            // API failed
            if(! is_array($blocks)) throw new Exception('API Failed');

            // Each block
            foreach($blocks as $block_data)
            {
                // Process block
                $block = $this->processBlock($block_data);

                // Save messages
                $this->saveMessages($block_data['_messages'], $block_data['block_time']);

                // Update balances
                UpdateBalances::dispatch($block);                
            }
        }
        catch(Exception $e)
        {
            Log::error($e->getMessage());
        }
        finally
        {
            // Keep going
            if($this->syncing) Artisan::call('update:blocks');
        }
    }

    /**
     * Counterparty API
     * https://counterparty.io/docs/api/#get_blocks
     *
     * @return mixed
     */
    private function getBlocks()
    {
        // ['first_index', '...', 'last_index']
        $block_indexes = range($this->first_index, $this->last_index);

        return $this->counterparty->execute('get_blocks', [
            'block_indexes' => $block_indexes,
        ]);
    }

    /**
     * Process block.
     * 
     * @param  array  $block_data
     * @return void
     */
    private function processBlock($block_data)
    {
        // Create block
        $block = Block::firstOrCreateBlock($block_data);

        // Update block
        if($this->guardAgainstInefficientSyncing($block))
        {
            // Extra data (non-XCP)
            UpdateBlock::dispatch($block);
        }

        return $block;
    }

    /**
     * Save messages.
     *
     * @param  array  $messages
     * @param  timestamp  $block_time
     * @return void
     */
    private function saveMessages($messages, $block_time)
    {
        // Each message
        foreach($messages as $message)
        {
            $this->saveMessage($message, $block_time);
        }
    }

    /**
     * Save message.
     *
     * @param  array  $message
     * @param  timestamp  $block_time
     * @return void
     */
    private function saveMessage($message, $block_time)
    {
        // Get bindings
        $bindings = $this->getBindings($message, $block_time);

        // Save message
        if(Message::firstOrCreateMessage($message, $bindings))
        {
            $this->executeCommand($message, $bindings);
        }
    }

    /**
     * Execute command.
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return void
     */
    private function executeCommand($message, $bindings)
    {
        try {
            // Handle accordingly
            if($message['command'] === 'insert')
            {
                // Insert
                $this->createEntry($message, $bindings);
            }
            elseif($message['command'] === 'update')
            {
                // Update
                $this->updateEntry($message, $bindings);
            }
            elseif($message['command'] === 'reorg')
            {
                // Reorgs
                $this->handleReorg($message, $bindings);
            }
        } catch (\Exception $e) {
            Log::info($message['block_index'] . ' ' . $message['message_index'] . ' ' . $message['command'] . ' ' . $message['category']);
            Log::info($e->getMessage());
        }
    }

    /**
     * Create entry (works for any & all models.)
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return void
     */
    private function createEntry($message, $bindings)
    {
        // Messages exist without txs
        if(isset($bindings['tx_index']))
        {
            // Create transaction (if exists)
            $this->createTransaction($message, $bindings);
        }

        // Create entry from this message (if valid)
        if($this->guardAgainstInvalidMessages($message, $bindings))
        {
            // Handle new addresses & address options
            $this->handleAddresses($message, $bindings);

            // First or create entry
            return $this->firstOrCreateEntry($message, $bindings);
        }
    }

    /**
     * Create transaction.
     * 
     * @param  array  $message
     * @param  array  $bindings
     * @return void
     */
    private function createTransaction($message, $bindings)
    {
        // Create transaction
        $transaction = Transaction::firstOrCreateTransaction($message, $bindings);

        // Update transaction
        if($this->guardAgainstInefficientSyncing($transaction))
        {
            UpdateTransaction::dispatch($transaction);
        }
    }

    /**
     * Handle addresses.
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return void
     */
    private function handleAddresses($message, $bindings)
    {
        // Creates addresses (if new)
        Address::createAddresses($message, $bindings);

        // Updates address options
        if($message['category'] === 'replace')
        {
            Address::updateAddressOptions($bindings);
        }
    }

    /**
     * First or create entry.
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return mixed
     */
    private function firstOrCreateEntry($message, $bindings)
    {
        // Get model name from message category
        $model_name = getModelName($message['category']);

        // Get an array to use for firstOrCreate
        $lookup = getLookupArrayFoC($message, $bindings);

        // Create entry
        if($lookup)
        {
            return $model_name::firstOrCreate($lookup, $bindings);
        }
        else
        {
            return $model_name::firstOrCreate($bindings);
        }
    }

    /**
     * Update entry (works for any & all models.)
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return void
     */
    private function updateEntry($message, $bindings)
    {
        // Get model name from message category
        $model_name = getModelName($message['category']);

        // Get an array to use for updateOrCreate
        $lookup = getLookupArrayUoC($message, $bindings);

        // Update entry
        return $model_name::updateOrCreate($lookup, $bindings);
    }

    /**
     * Handle reorg.
     *
     * @param  array  $message
     * @param  array  $bindings
     * @return void
     */
    private function handleReorg($message, $bindings)
    {
        HandleRollback::dispatchNow($message, $bindings);
    }

    /**
     * Get bindings.
     *
     * @param  array  $message
     * @param  timestamp  $block_time
     * @return array
     */
    private function getBindings($message, $block_time)
    {
        // Message's binded values
        $bindings = json_decode($message['bindings'], true);

        // Block's confirmation time
        $confirmed_at = Carbon::createFromTimestamp($block_time)->toDateTimeString();

        // Array of message's data
        return array_merge($bindings, ['confirmed_at' => $confirmed_at]);
    }

    /**
     * Guard against inefficient syncing.
     * 
     * @param  mixed  $model
     * @return boolean
     */
    private function guardAgainstInefficientSyncing($model)
    {
        if($model instanceof Block)
        {
            // Advanced +
            // Not syncing +
            // Block not processed +
            // Bitcoin Core API available
            return config('xcp-core.advanced') && ! $this->syncing && ! $model->processed_at && config('xcp-core.bc.api');
        }
        else
        {
            // Advanced +
            // Not syncing +
            // Model not processed +
            return config('xcp-core.advanced') && ! $this->syncing && ! $model->processed_at;
        }
    }

    /**
     * Guard against invalid messages
     * 
     * @param  array  $messages
     * @param  array  $bindings
     * @return boolean
     */
    private function guardAgainstInvalidMessages($message, $bindings)
    {
        // Create transactions, but not entries for invalid messages
        if(isset($bindings['status']) && substr(trim($bindings['status']), 0, 7) === 'invalid')
        {
            return false;
        }

        return true;
    }
}