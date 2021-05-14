<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FillEmptyOrderColumns
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $modelIndexSegment = $request->segment(2);
        if ($modelIndexSegment && in_array($modelIndexSegment, config('defaults.order_column_models'))) {
            $className = str_replace('-', '', Str::singular(Str::title($modelIndexSegment)));
            $className = "App\\Models\\$className";
            Controller::fillEmptyOrderColumnValues($className);
        }

        return $next($request);
    }
}
