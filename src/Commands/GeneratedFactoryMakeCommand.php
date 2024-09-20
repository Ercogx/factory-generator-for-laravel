<?php

namespace Ercogx\FactoryGeneratorLaravel\Commands;

use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Illuminate\Support\Str;
use Ercogx\FactoryGeneratorLaravel\FakerMapper;

class GeneratedFactoryMakeCommand extends FactoryMakeCommand
{
    protected $signature = 'make:generate-factory {name} {--model=}';

    protected $description = 'Create a new model factory with an attempt to generate a field faker';

    protected function getStub()
    {
        return __DIR__.'/../../resources/stubs/factory-generator.stub';
    }

    protected function buildClass($name)
    {
        $factory = class_basename(Str::ucfirst(str_replace('Factory', '', $name)));

        $namespaceModel = $this->option('model')
            ? $this->qualifyModel($this->option('model'))
            : $this->qualifyModel($this->guessModelName($name));

        $model = class_basename($namespaceModel);

        $namespace = $this->getNamespace(
            Str::replaceFirst($this->rootNamespace(), 'Database\\Factories\\',
                $this->qualifyClass($this->getNameInput()))
        );

        $replace = [
            '{{ factoryNamespace }}' => $namespace,
            'NamespacedDummyModel' => $namespaceModel,
            '{{ namespacedModel }}' => $namespaceModel,
            '{{namespacedModel}}' => $namespaceModel,
            'DummyModel' => $model,
            '{{ model }}' => $model,
            '{{model}}' => $model,
            '{{ factory }}' => $factory,
            '{{factory}}' => $factory,
            '{{ generated }}' => resolve(FakerMapper::class)->handle($namespaceModel),
        ];

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }
}
