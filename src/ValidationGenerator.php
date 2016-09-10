<?php
namespace Sands\Validation;

use Event;

class ValidationGenerator
{
    public function generate($controller, $resource = false)
    {
        $validationPath = app_path('Validations');
        if (!app('files')->exists($validationPath)) {
            app('files')->makeDirectory($validationPath);
        }

        $contents = file_get_contents(realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'validation.stub'));
        $contents = str_replace('ClassName', $controller, $contents);
        $contents = str_replace('Methods', $this->methods($resource), $contents);

        $filePath = $validationPath . DIRECTORY_SEPARATOR . $controller . 'Rules.php';
        file_put_contents($filePath, $contents);

        Event::fire('sands.validation::MakeRules', $controller . 'Rules');
    }

    public function methods($resource = false)
    {
        $methods = '';
        if ($resource) {
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
