<?php

namespace App\Jobs\Middleware;

use Illuminate\Support\Facades\Redis;

class RateLimited
{
    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        Redis::funnel('zoho_syncing')
             ->limit(1)
             ->then(function () use ($job, $next) {
                 $next($job);
             }, function () use ($job) {
                 // Could not obtain lock...
                 return $job->release(10);
             });
    }
}
