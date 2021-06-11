<?php

namespace DIJ\ApiFactories\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class ApiFactoryMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:api-factory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new a class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'ApiFactory';

    protected function getStub()
    {
        return $this->resolveStubPath('/stubs/api-factory.stub');
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     */
    protected function buildClass($name)
    {
        $factory = class_basename(Str::ucfirst(str_replace('Factory', '', $name)));
        $namespace = 'Tests\\Factories';

        $replace = [
            '{{ factoryNamespace }}' => $namespace,
            '{{ factory }}' => $factory,
            '{{factory}}' => $factory,
        ];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = (string) Str::of($name)->replaceFirst($this->rootNamespace(), '')->finish('Factory');

        return $this->laravel->basePath() . '/tests/Factories/' . str_replace('\\', '/', $name) . '.php';
    }
}
