<?php

namespace Ercogx\FactoryGeneratorLaravel;

use Ercogx\FactoryGeneratorLaravel\Commands\GeneratedFactoryMakeCommand;
use Illuminate\Support\ServiceProvider;

class FactoryGeneratorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GeneratedFactoryMakeCommand::class,
            ]);
        }
    }
}
