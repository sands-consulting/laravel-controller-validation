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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $validationPath = app_path('Validations');
        if (!app('files')->exists($validationPath)) {
            app('files')->makeDirectory($validationPath);
        }

        $contents = file_get_contents(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'validation.stub'));
        $contents = str_replace('ClassName', $this->argument('controller'), $contents);
        $contents = str_replace('Methods', $this->methods(), $contents);

        $filePath = $validationPath . DIRECTORY_SEPARATOR . $this->argument('controller') . 'Rules.php';
        file_put_contents($filePath, $contents);
        $this->info("$filePath written.");
    }

    public function methods()
    {
        $methods = '';
        if ($this->option('resource')) {
            $methods .= "    protected function store(\$request, \$params)
    {
        return [

        ];
    }

    protected function update(\$request, \$params)
    {
        return [

        ];
    }";
        }
        return $methods;
    }
}
