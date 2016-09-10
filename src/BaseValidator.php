<?php

namespace Sands\Validation;

class BaseValidator
{
    public function validate($request, $method, $params)
    {
        $dataMethod = "{$method}Data";
        if (method_exists($this, $dataMethod)) {
            $data = $this->$dataMethod($request, $params);
        } else {
            $data = $request->all();
        }
        return app('validator')->make($data, $this->{$method}($request, $params));
    }
}
