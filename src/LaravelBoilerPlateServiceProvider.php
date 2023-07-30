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
        foreach (scandir('../Helpers') as $helperFile) {
            if (in_array($helperFile, ['.', '..'])) {
                continue;
            }

            require '../Helpers/'. $helperFile;
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
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeEnumClass::class,
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeHelper::class,
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeIntegrationTest::class,
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeRepositoryClass::class,
                \Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators\MakeServiceClass::class,
            ]);
        }
    }
}
