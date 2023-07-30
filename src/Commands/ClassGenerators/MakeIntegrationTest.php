<?php

namespace Bensondevs\LaravelBoilerPlate\Commands\ClassGenerators;

use Bensondevs\LaravelBoilerPlate\Services\Utility\ClassGeneratorService;
use Illuminate\Console\Command;

class MakeIntegrationTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:integration-test {test : Name of integration test class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create integration test class.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (!file_exists(base_path('tests/Integration'))) {
            
        }
        
        $testName = $this->argument('test');

        $generatorService = (new ClassGeneratorService)
            ->setType('integration_test')
            ->setFileName($testName);

        if ($exists = file_exists($generatorService->getFullDesignatedPath())) {
            $question = 'The class is already exist. Are you sure want to override the existing class?';
            if (! $this->confirm($question)) {
                $this->error('Class overriding process aborted.');
                return 0;
            }
        }

        $className = $generatorService->getClassName();
        $type = $exists ? 'overridden' : 'created';
        $generatorService->generate() ?
            $this->info($className . ' has been ' . $type . ' successfully!') :
            $this->error('Failed to generate the class! Please check permission');

        return 0;
    }
}
