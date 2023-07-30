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
                \RafCom\Commands\ClassGenerators\MakeEnumClass::class,
                \RafCom\Commands\ClassGenerators\MakeHelper::class,
                \RafCom\Commands\ClassGenerators\MakeIntegrationTest::class,
                \RafCom\Commands\ClassGenerators\MakeRepositoryClass::class,
                \RafCom\Commands\ClassGenerators\MakeServiceClass::class,
            ]);
        }
    }
}
