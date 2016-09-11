#Sands\Validation

Automatic controller validation for Laravel 5+

##Installation

```bash
$ composer require sands/laravel-controller-validation
```

In `config/app.php` add `Sands\Validation\ValidationServiceProvider` inside the `providers` array:

```php
'providers' => [
     ...
     Sands\Validation\ValidationServiceProvider::class,
     ...
]
```

In `app/Http/Kernel.php` add `Sands\Validation\ValidationMiddleware` inside the `$routeMiddlware` array:

```php
protected $routeMiddleware = [
    ...
    'validation' => Sands\Validation\ValidationMiddleware::class,
    ...
]
```

##Usage

Let's say that our we have a resourceful Controller to manage application users and inside the routes file we register routes for the controller as such:

```php
Route::resource('users', 'UsersController');
```

First of all, let's make the validation file for the UsersController:

```bash
$ artisan make:validation UsersController --resource
```

This command will generate a new file `UsersControllerRules.php` inside `app/Validations` folder. If the `--resource` argument is given, the validation file will automatically have `store` and `update` methods inside it.



The validation methods must return an array of [Laravel validation rules](https://laravel.com/docs/5.3/validation#available-validation-rules).

Lastly, associate the validation rules to the controller as such:

```php
// in app/Http/Controllers/UsersController.php

public function __construct()
{
    $this->middleware('validation');
}
```

Before the `store` or `update` method in `UsersController`is invoked, Laravel will run validation according to the rules returned in `UsersControllerRules@store` or `UsersControllerRules@update` method.

Alternatively, you can attach the middleware inside your routes file via route groups:

```php
Route::group(['middleware' => ['validation']], function() {
    Route::resource('users', 'UsersController');
});
```

You can also define which class the validation middleware will get its rules from:

```php
// in app/Http/Controllers/UsersController.php

use App\Validations\UsersControllerRules;

...

public function __construct()
{
    $this->middleware('validation:' . UsersControllerRules::class);
}
```

If the request does not satisfy any validation rules, the users will be redirected back with the `$errors` array populated with the validation errors. The errors can be shown to the user as such:

```php
@if (count($errors) > 0)
<div class="alert alert-danger">
    <ul>
    @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
    @endforeach
    </ul>
</div>
@endif
```

If the request wants JSON as determined by `Request::wantsJson()`, the middleware will return HTTP error `422` with JSON payload as such:

```json
{
    "errors": [validation errors]
}
```

By default the validation rules will be validated against all user inputs as returned by `app('request')->all()`. If you want to override the data validated, you can define the method in the validation rules file. For example if we want to only validate the username when the `store` validation method is called inside the UsersControllerRules, we can define a `storeData ` method as following:

```php
// app/Validations/UsersControllerRules.php
...
protected function storeData($request, $params)
{
    return $request->only('username');
}

protected function store($request, $params)
{
    return [
        'username' => 'required|unique:users'
    ];
}
```

##Listeners

This package listens to the `sands.generator::MakeController` event and creates the validation rules.

##Events

This package fires the `sands.validation::MakeRules` event whenever a new validation rules file is created. The first argument of the event is `ControllerNameRules` where `ControllerName` is the name of the controller.

##MIT License

Copyright (c) 2016 Sands Consulting Sdn Bhd


Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.