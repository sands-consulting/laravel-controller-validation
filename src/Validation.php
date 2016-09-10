<?php

namespace Sands\Validation;

use ReflectionClass;

class Validation
{
    private $cached = [];

    public function check($controller, $action, $parameters = [], $rules)
    {
        //  Handle null parameters
        $parameters = $parameters ?: [];

        if ($rules) {
            $handler = new $rules();
        } else {
            $handler = $this->getValidationRules($controller);
        }
        if (!$handler || !method_exists($handler, $action)) {
            return;
        }

        return call_user_func_array([$handler, 'validate'], [app('request'), $action, $parameters]);
    }

    public function checkCurrentRoute($rules = null)
    {
        $route = app('router')->current();
        list($controller, $action) = explode('@', $route->getAction()['uses']);
        $controller = (new ReflectionClass($controller))->getShortName();
        return $this->check($controller, $action, $route->parameters(), $rules);
    }

    protected function getValidationRules($controller)
    {
        if (!isset($this->cached[$controller])) {
            if (!app('files')->exists(app_path('Validations' . DIRECTORY_SEPARATOR . $controller . 'Rules.php'))) {
                return;
            }
            $validation = "\\App\\Validations\\{$controller}Rules";
            $this->cached[$controller] = new $validation;
        }
        return $this->cached[$controller];
    }
}
