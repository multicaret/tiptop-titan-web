<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\V1\Auth\AuthController;
use Closure;

class CheckSuspension
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
        $isSuspended = AuthController::isSuspended($request->user());
        if ($isSuspended['enabled']) {
            return response([
                'isSuspended' => $isSuspended,
                'success' => false,
            ]);
        }

        return $next($request);
    }
}
