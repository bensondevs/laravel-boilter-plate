<?php

namespace Bensondevs\LaravelBoilerPlate;

use Illuminate\Support\ServiceProvider;

class LaravelBoilerPlateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerHelpers();
        $this->registerConsoleCommands();
    }

    /**
     * Register the package helpers.
     *
     * @return void
     */
    private function registerHelpers(): void
    {
        foreach (scandir(__DIR__ . '/Helpers') as $helperFile) {
            if (in_array($helperFile, ['.', '..'])) {
                continue;
            }

            require __DIR__ . '/Helpers/' . $helperFile;
        }
    }

    /**
     * Register application console commands.
     *
     * @return void
     */
    private function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeEnum::class,
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeHelper::class,
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeIntegrationTest::class,
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeRepository::class,
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeService::class,
            ]);
        }
    }
}
