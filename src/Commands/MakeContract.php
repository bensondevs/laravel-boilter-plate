<?php

namespace Bensondevs\LaravelBoilerPlate\Commands;

use Illuminate\Console\Command;

class MakeContract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:contract {argument}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Destination of generated class file path.
     *
     * @var string
     */
    protected $destinationPath = '';

    /**
     * Class name of the generated class.
     *
     * @var string
     */
    protected $className = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->destinationPath = app_path('Contracts');
    }

    /**
     * Get name and group of the argument given to the command.
     *
     * @param string $argument
     * @return void
     */
    private function catchNameAndGroup(string $argument): void
    {
        $group = '';
        $name = $argument;

        $explode = explode('/', $argument);
        if (count($explode) > 1) {
            $name = array_pop($explode);
            $group = implode('/', $explode);
        }

        $this->setClassGroup($group);
        $this->setClassName($name);
    }

    /**
     * Set group of the class.
     *
     * This will adjust the destination path where the class will fall into.
     * The group specified will determine to which sub-folder this class will
     * be created into.
     *
     * @param string $path
     * @return void
     */
    private function setClassGroup(string $path = ''): void
    {
        if (! $path) return;

        // Add base '../app/Contracts' if the inserted path is not '../app/Contracts'.
        if (! is_str_starts_with($path, app_path('Contracts'))) {
            $path = app_path(concat_paths(['Contracts', $path]));
        }

        $this->destinationPath = $path;
    }

    /**
     * Get designated class group namespace.
     *
     * @return string
     */
    private function getClassGroupNameSpace(): string
    {
        $path = $this->destinationPath;

        // Strip path from contracts base path
        $path = str_replace(app_path('Contracts'), '', $path);

        // Strip "/" from last character if any
        if (is_last_character($path, '/')) {
            $path = substr($path, 0, -1);
        }

        // Replace "/" with "\\" to be implemented in file content
        return str_replace('/', '\\', $path);
    }

    /**
     * Set class name of the generated class.
     *
     * @param string $name
     * @return void
     */
    private function setClassName(string $name): void
    {
        $this->className = $name;
    }

    /**
     * Get class file name (.php) for the contract.
     *
     * @return string
     */
    private function getClassFileName(): string
    {
        $filename = $this->className;

        return $filename . '.php';
    }

    /**
     * Get full designated path of a contract file.
     *
     * @return string
     */
    private function getFullDesignatedPath(): string
    {
        $folderPath = $this->destinationPath;
        $fileName = $this->getClassFileName();

        return concat_paths([$folderPath, $fileName], true);
    }

    /**
     * Get content of the stub/template file.
     *
     * @return string
     */
    private function getStubContent(): string
    {
        $stubPath = resource_path('stubs/Contract.stub');

        return file_get_contents($stubPath);
    }

    /**
     * Implement variable resource to the template string content and
     * create new file with the compiled template string with the variables.
     *
     * @param string $template
     * @param array $resource
     * @return bool
     */
    private function makeClass(string $template, array $resource): bool
    {
        $fileFullPath = $this->getFullDesignatedPath();

        // Check whether the file path is already exist
        if (file_exists($fileFullPath)) {
            $this->error('The contract file already exists!');
            return false;
        }

        $instances = array_keys($resource);
        $values = array_values($resource);
        $compiledContent = str_replace($instances, $values, $template);

        return file_put_contents($fileFullPath, $compiledContent);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $argument = $this->argument('argument');
        $this->catchNameAndGroup($argument);

        $templateContent = $this->getStubContent();
        $this->makeClass($templateContent, [
            '{group}' => $this->getClassGroupNameSpace(),
            '{name}' => $this->className,
        ]);

        $this->info('Contract has been created.');
    }
}
