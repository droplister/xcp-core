<?php

namespace Droplister\XcpCore\App\Console\Commands;

use Droplister\XcpCore\App\Jobs\UpdateMempool;
use Illuminate\Console\Command;

class UpdateMempoolCommand extends Command
{
    /*
    |--------------------------------------------------------------------------
    | Update Mempool Command
    |--------------------------------------------------------------------------
    |
    | The purpose of this command is to periodically index the mempool
    | not heavily used, but useful overtime, if saved, for things
    | like fee estimation and time-to-confirmation data.
    |
    */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:mempool';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Mempool';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Update
        UpdateMempool::dispatch();
    }
}