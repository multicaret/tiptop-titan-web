<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     *
     * @param  array  $roles
     *
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if ( ! auth()->check()) {
            if (Str::is('*/admin*', $request->url())) {
                return response()->view('admin.login');
            } elseif (Str::is('*/dashboard*', $request->url())) {
                return response()->view('admin.login');
            } elseif (Str::is('*/api/*', $request->url())) {
                return response([
                    'data' => trans('auth.failed')
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response()->view('auth.login');
        } elseif ( ! in_array($request->user()->role, $roles) && ! Str::is('*/ajax*', $request->url())) {
            return redirect('/');
        }

        return $next($request);
    }
}
