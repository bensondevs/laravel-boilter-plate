<?php

namespace Bensondevs\LaravelBoilerplate\Commands\ClassGenerators;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\ConsoleOutput;

class MakeUnitTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:unit-test {argument}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to make unit test';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->output = new ConsoleOutput();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $argument = $this->argument('argument');

        Artisan::call('make:test', [
            'name' => $argument,
            '--unit' => true,
        ], $this->output);

        return 0;
    }
}
