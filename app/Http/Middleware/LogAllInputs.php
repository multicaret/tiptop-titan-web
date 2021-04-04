<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Log;

class LogAllInputs
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('app.debug')) {
            $route = Route::getRoutes()->match($request);

            Log::info(sprintf('API "%s" to "%s"', $route->methods()[0], $route->uri()), [
                'action' => $route->getActionName(),
                'uri' => $route->uri(),
                'requestAll' => $request->all(),
            ]);
        }

        return $next($request);
    }
}
