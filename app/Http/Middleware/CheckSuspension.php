<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckSuspension
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
        if ( ! is_null($request->user()) && ($request->user()->status == User::STATUS_INACTIVE)) {
            return response([
                'isSuspended' => true,
                'success' => false,
            ]);
        }

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
