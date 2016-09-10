<?php

namespace Sands\Validation;

use Illuminate\Console\Command;

class ValidationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:validation
        {controller : The controller to validate e.g UsersController}
        {--R|resource : add store and update methods}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new validation rules class';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->generator = new ValidationGenerator();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->generator->generate($this->argument('controller'), $this->option('resource'));
        $this->info("{$this->argument('controller')}Rules.php written.");
    }
}
