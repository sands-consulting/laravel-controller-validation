<?php

namespace Sands\Validation;

use Closure;

class ValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $rules = null)
    {
        $validation = app('validation')->checkCurrentRoute();
        if ($validation && $validation->fails()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => $validation->errors()->all(),
                ]);
            }
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validation);
        }
        return $next($request);
    }

}
