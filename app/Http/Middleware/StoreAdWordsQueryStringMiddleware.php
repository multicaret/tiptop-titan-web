<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StoreAdWordsQueryStringMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $adWordsQueryStrings = [
            'utm_source',
            'utm_medium',
            'utm_campaign',
            'utm_term',
            'utm_content',
        ];
        foreach ($adWordsQueryStrings as $adWordsQueryString) {
            if ($request->has($adWordsQueryString)) {
                session()->put($adWordsQueryString, $request->input($adWordsQueryString));
            }
        }

        return $next($request);
    }
}
