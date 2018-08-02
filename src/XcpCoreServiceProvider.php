<?php

namespace Droplister\XcpCore;

use Event;
use Droplister\XcpCore\App\Events\IssuanceWasCreated;
use Droplister\XcpCore\App\Listeners\CreateAssetFromIssuance;
use Droplister\XcpCore\App\Listeners\UpdateAssetFromIssuance;
use Illuminate\Support\ServiceProvider;

class XcpCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->handleConfig();
        $this->handleEvents();
        $this->handleCommands();
        $this->handleMigrations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Publish Config
     *
     * @return void
     */
    private function handleConfig()
    {
        $configPath = __DIR__ . '/config/xcp-core.php';
        $this->publishes([$configPath => config_path('xcp-core.php')], 'xcp-core');
    }

    /**
     * Load Commands
     *
     * @return void
     */
    private function handleCommands()
    {
        if($this->app->runningInConsole())
        {
            $this->commands([
                App\Console\Commands\UpdateBlocksCommand::class,
                App\Console\Commands\UpdateIndexCommand::class,
                App\Console\Commands\UpdateSupplyCommand::class,
            ]);
        }
    }

    /**
     * Handle Events
     *
     * @return void
     */
    private function handleEvents()
    {
        Event::listen(IssuanceWasCreated::class, CreateAssetFromIssuance::class);
        Event::listen(IssuanceWasCreated::class, UpdateAssetFromIssuance::class);
    }

    /**
     * Load Migrations
     *
     * @return void
     */
    private function handleMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
    }
}