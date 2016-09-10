<?php

namespace Sands\Validation;

use Illuminate\Support\ServiceProvider;

class ValidationServiceProvider extends ServiceProvider
{
    protected $commands = [
        ValidationCommand::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // register commands
        $this->commands($this->commands);

        // register singleton
        app()->singleton('sands.validation', function () {
            return new Validation;
        });

        app('events')->listen('sands.generator::MakeController', function ($controller) {
            (new ValidationGenerator)->generate($controller, true);
        });
    }
}
