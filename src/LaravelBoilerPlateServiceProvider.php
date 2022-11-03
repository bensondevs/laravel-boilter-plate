<?php

namespace Bensondevs\LaravelBoilerPlate;

use Bensondevs\LaravelBoilerPlate\Commands\MakeContract;
use Bensondevs\LaravelBoilerPlate\Commands\MakeEnum;
use Bensondevs\LaravelBoilerPlate\Commands\MakeHelper;
use Bensondevs\LaravelBoilerPlate\Commands\MakeIntegrationTest;
use Bensondevs\LaravelBoilerPlate\Commands\MakeRepository;
use Bensondevs\LaravelBoilerPlate\Commands\MakeService;
use Bensondevs\LaravelBoilerPlate\Commands\MakeTrait;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class LaravelBoilerPlateServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerCommands();
    }

    /**
     * Register the console commands for the package.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeContract::class,
                MakeEnum::class,
                MakeHelper::class,
                MakeIntegrationTest::class,
                MakeRepository::class,
                MakeService::class,
                MakeTrait::class,
            ]);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            MakeContract::class,
            MakeEnum::class,
            MakeHelper::class,
            MakeIntegrationTest::class,
            MakeRepository::class,
            MakeService::class,
            MakeTrait::class,
        ];
    }
}