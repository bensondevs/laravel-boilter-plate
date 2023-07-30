<?php

namespace Bensondevs\LaravelBoilerplate\Commands\ClassGenerators;

use Bensondevs\LaravelBoilerplate\Services\Utility\ClassGeneratorService;
use Illuminate\Console\Command;

class MakeTrait extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:trait {trait : Name of trait}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create trait.';

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
        if (!file_exists(app_path('Traits'))) {
            mkdir(app_path('Traits'), 0777, true);
        }
        
        $traitName = $this->argument('trait');

        $generatorService = (new ClassGeneratorService)
            ->setType('trait')
            ->setFileName($traitName);

        if ($exists = file_exists($generatorService->getFullDesignatedPath())) {
            $question = 'The class is already exist. Are you sure want to override the existing class?';
            if (! $this->confirm($question)) {
                $this->error('Class overriding process aborted.');
                return;
            }
        }

        $className = $generatorService->getClassName();
        $type = $exists ? 'overridden' : 'created';
        $generatorService->generate() ?
            $this->info($className . ' has been ' . $type . ' successfully!') :
            $this->error('Failed to generate the class! Please check permission');
    }
}
