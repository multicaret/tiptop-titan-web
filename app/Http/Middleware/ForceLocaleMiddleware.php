<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class ForceLocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Str::is('*/admin*', $request->url())) {
            localization()->setLocale('en');
        }
        if (Str::is('*/dashboard*', $request->url())) {
            localization()->setLocale('ar');
        }

        return $next($request);
    }
}
